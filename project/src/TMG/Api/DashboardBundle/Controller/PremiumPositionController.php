<?php

namespace TMG\Api\DashboardBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use TMG\Api\GlobalBundle\Controller\GlobalController;

use TMG\Api\ApiBundle\Entity\Books;
use TMG\Api\ApiBundle\Entity\Contract;

class PremiumPositionController extends GlobalController
{
    // Utils
    protected $utils;

    // Entity
    protected $booksRepo;
    protected $contractRepo;

    public function initialize()
    {
        $this->utils = $this->container->get('dash.utils');
        $this->booksRepo = $this->em->getRepository('ApiBundle:Books');
        $this->contractRepo = $this->em->getRepository('ApiBundle:Contract');
    }

    public function indexAction(Request $request)
    {
        $reports = [];
        $book = $request->query->get('book', '');
        $position = $request->query->get('position', '');
        $start = $request->query->get('start', '');
        $end = $request->query->get('end', '');

        $bookId = $this->booksRepo->getBookIdByCode($book);

        $issues = $this->utils->getIssues($start, $end);
        $contracts = $this->contractRepo->buildPremiumPositionReport(
            $bookId['id'],
            (int) $start,
            (int) $end,
            $position
        );

        foreach ($contracts as $result) {
            if ($result['propertyNumber']) {
                $accountNum = $result['propertyNumber'];
            } else {
                $accountNum = $result['axNumber'];
            }

            if ($result['masterOrderE1Account']) {
                $orderNumber = $result['masterOrderE1Account'];
            } elseif ($result['masterOrderAccount']) {
                $orderNumber = $result['masterOrderAccount'];
            } elseif ($result['masterOrderNumber']) {
                $orderNumber = $result['masterOrderNumber'];
            } else {
                $orderNumber = $result['orderNumber'];
            }


            $row = array(
                'order' => $orderNumber,
                'result' => $result,
                'customer_number' => $accountNum,
                'id' => $result['hash'],
            );

            foreach ($issues as $issue) {
                if (!isset($reports["$issue"])) {
                    $reports["$issue"] = [];
                }
                if ($result['startIssue'] <= $issue &&
                    $result['endIssue'] >= $issue) {
                        array_push($reports["$issue"], $row);
                }
            }
        }
        return new JsonResponse([
            'success' => true,
            'results' => $reports,
        ]);

    }
}
