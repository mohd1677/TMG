<?php

namespace TMG\Api\DashboardBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use TMG\Api\GlobalBundle\Controller\GlobalController;

use TMG\Api\ApiBundle\Entity\Property;

class ReportController extends GlobalController
{
    // Entity
    protected $propertyRepo;
    protected $contractRepo;

    public function initialize()
    {
        $this->propertyRepo =$this->em->getRepository('ApiBundle:Property');
        $this->contractRepo = $this->em->getRepository('ApiBundle:Contract');
    }

    /**
     * API for counting Missing Trip Stay Win Subdomain
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function missingSubdomainCountAction(Request $request)
    {
        $count = $this->contractRepo->missingSubdomainCount();

        return new JsonResponse([
            'success' => true,
            'results' => $count,
        ]);
    }

    /**
     * API for Missing Trip Stay Win Subdomain
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function missingSubdomainListAction(Request $request)
    {
        $list = $this->contractRepo->missingSubdomainList();

        return new JsonResponse([
            'success' => true,
            'results' => $list,
        ]);
    }

    public function missingRequiredFaxCountAction(Request $request)
    {
        $count = $this->propertyRepo->missingRequiredFaxCount();

        return new JsonResponse([
            'success' => true,
            'results' => $count,
        ]);
    }

    public function missingRequiredFaxListAction(Request $request)
    {
        $results = [];
        $rows = $this->propertyRepo->missingRequiredFaxList();
        foreach ($rows as $row) {
            array_push($results, $row);
        }

        return new JsonResponse([
            'success' => true,
            'results' => $results,
        ]);
    }

    public function missingRequiredEmailCountAction(Request $request)
    {
        $count = $this->propertyRepo->missingRequiredEmailCount();

        return new JsonResponse([
            'success' => true,
            'results' => $count,
        ]);
    }

    public function missingRequiredEmailListAction(Request $request)
    {
        $results = [];
        $rows = $this->propertyRepo->missingRequiredEmailList();
        foreach ($rows as $row) {
            array_push($results, $row);
        }

        return new JsonResponse([
            'success' => true,
            'results' => $results,
        ]);
    }

    public function missingAccountNumbersForSharedAdsCount(Request $request)
    {
        $count = $this->contractRepo->missingAccountNumbersForSharedAdsCount();

        return new JsonResponse([
            'success' => true,
            'results' => $count,
        ]);
    }

    public function missingAccountNumbersForSharedAdsList(Request $request)
    {
        $results = [];
        $rows = $this->contractRepo->missingAccountNumbersForSharedAdsList();
        foreach ($rows as $row) {
            array_push($results, $row);
        }

        return new JsonResponse([
            'success' => true,
            'results' => $results,
        ]);
    }
}
