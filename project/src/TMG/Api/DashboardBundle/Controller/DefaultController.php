<?php

namespace TMG\Api\DashboardBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use TMG\Api\GlobalBundle\Controller\GlobalController;

use TMG\Api\ApiBundle\Entity\Books;
use TMG\Api\ApiBundle\Entity\Contract;
use TMG\Api\ApiBundle\Entity\Reputation;
use TMG\Api\ApiBundle\Entity\Social;
use TMG\Api\ApiBundle\Entity\TollFree;
use TMG\Api\ApiBundle\Entity\Property;

class DefaultController extends GlobalController
{
    // Entity
    protected $booksRepo;
    protected $contractRepo;
    protected $propertyRepo;
    protected $reputationRepo;
    protected $socialRepo;
    protected $tollFreeRepo;

    public function initialize()
    {
        $this->booksRepo = $this->em->getRepository('ApiBundle:Books');
        $this->propertyRepo = $this->em->getRepository('ApiBundle:Property');
        $this->contractRepo = $this->em->getRepository('ApiBundle:Contract');
        $this->reputationRepo = $this->em->getRepository('ApiBundle:Reputation');
        $this->socialRepo = $this->em->getRepository('ApiBundle:Social');
        $this->tollFreeRepo = $this->em->getRepository('ApiBundle:TollFree');
    }

    public function bookListAction(Request $request)
    {
        $bookList = $this->booksRepo->getBookList();
        return new JsonResponse([
            'success' => true,
            'results' => $bookList,
        ]);
    }

    public function premiumPositionListAction(Request $request)
    {
        $premiums = [];
        $pList = $this->contractRepo->getPremiumPositionList();
        foreach ($pList as $result) {
            array_push($premiums, $result[1]);
        }

        return new JsonResponse([
            'success' => true,
            'results' => $premiums,
        ]);
    }

    // Check Property
    public function checkPropertyAction(Request $request, $id)
    {
        $results = array(
            'reputation' => false,
            'social' => false,
            'toll-free' => false,
            'online-contract' => false,
        );
        $property = $this->propertyRepo->findOneBy(array(
            'hash' => $id,
        ));
        if ($property) {
            $propId = $property->getId();
            $reputation = $this->reputationRepo->activeReputation($propId);
            if ($reputation) {
                $results['reputation'] = $reputation['active'];
            }
            $social = $this->socialRepo->activeSocial($propId);
            if ($social) {
                $results['social'] = $social['active'];
            }
            $tollFree = $this->tollFreeRepo->activeTollFree($propId);
            if ($tollFree) {
                $results['toll-free'] = true;
            }
            $hasOnlineContract = $this->contractRepo->hasOnlineContract($propId);
            if ($hasOnlineContract) {
                $results['online-contract'] = true;
            }
        }

        return new JsonResponse([
            'success' => true,
            'results' => $results,
        ]);
    }
}
