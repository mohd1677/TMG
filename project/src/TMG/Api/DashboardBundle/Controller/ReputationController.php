<?php

namespace TMG\Api\DashboardBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use TMG\Api\GlobalBundle\Controller\GlobalController;
use FOS\RestBundle\Controller\Annotations as Rest;
use TMG\Api\ReputationBundle\Handler\ReputationSurveyHandler;

class ReputationController extends GlobalController
{
    // Utils
    protected $utils;
    protected $socialKey;
    protected $uploadUrl;

    // Entity
    protected $propertyRepo;
    protected $reputationRepo;
    protected $reputationReviewRepo;
    /** @var  ReputationSurveyHandler */
    protected $reputationSurveyHandler;

    public function initialize()
    {
        $this->utils = $this->container->get('dash.utils');
        $this->socialKey = $this->container->getParameter('engage_social_key');
        $this->uploadUrl = $this->container->getParameter('engage_upload_url');
        $this->propertyRepo =$this->em->getRepository('ApiBundle:Property');
        $this->reputationRepo = $this->em->getRepository('ApiBundle:Reputation');
        $this->reputationReviewRepo = $this->em->getRepository('ApiBundle:ReputationReview');
        $this->reputationSurveyHandler = $this->container->get('tmg.reputation.survey.handler');
    }

    // Overview
    public function indexAction(Request $request, $id)
    {
        $results = [];
        $ts = new \DateTime();
        $ts = $ts->getTimeStamp();
        $results['ts'] = $ts;
        $range = $request->query->get('range', '');
        $end = new \DateTime('now');
        $end = $end->format('Y-m-d');
        $results['end'] = $end;
        $rangeResult = $this->utils->handleRange($range);
        if (!$range) {
            $results['range'] = $rangeResult['range'];
        } else {
            $results['range'] = $range;
        }
        $start = $rangeResult['start'];
        $results['start'] = $start;
        $results['reporting'] = null;
        $results['competitors'] = null;
        $results['influence'] = null;

        $property = $this->propertyRepo->findOneBy(array(
            'hash' => $id,
        ));
        if (!$property) {
            $results['account'] = null;
            $results['key'] = null;
            $results['social'] = null;
        } else {
            $propId = $property->getId();
            $aNum = $property->getAxNumber();
            $results['account'] = $aNum;
            $sKey = $aNum.'|'.$ts.'|'.$this->socialKey;
            $sKey = md5($sKey);
            $results['key'] = $sKey;

            // Social
            $results['social'] = $this->utils->handleSocial($propId, $aNum, $ts, $sKey);
            // Reporting
            if ($start) {
                $start = $start->format('Y-m-d');
                $results['start'] = $start;
            }
            $results['reporting'] = $this->utils->handleReporting($propId, $end, $start);
            // Competitors
            $results['competitors'] = $this->utils->handleCompetitors($propId, $end, $start);
            // influence
            $results['influence'] = $this->utils->handleInfluence($propId, $end, $start);
        }

        return new JsonResponse([
            'success' => true,
            'results' => $results,
        ]);
    }

    // Influence
    public function influenceAction(Request $request, $id)
    {
        $results = [];
        $ts = new \DateTime();
        $ts = $ts->getTimeStamp();
        $results['ts'] = $ts;
        $range = $request->query->get('range', '');
        $end = new \DateTime('now');
        $end = $end->format('Y-m-d');
        $results['end'] = $end;
        $rangeResult = $this->utils->handleRange($range);
        if (!$range) {
            $results['range'] = $rangeResult['range'];
        } else {
            $results['range'] = $range;
        }
        $start = $rangeResult['start'];
        $results['start'] = $start;
        $results['reporting'] = null;
        $results['influence'] = null;
        $results['emails'] = null;


        $property = $this->propertyRepo->findOneBy(array(
            'hash' => $id,
        ));

        if (!$property) {
            $results['account'] = null;
            $results['key'] = null;
            $results['soclai'] = null;
        } else {
            $propId = $property->getId();
            $aNum = $property->getAxNumber();
            $results['account'] = $aNum;
            $sKey = $aNum.'|'.$ts.'|'.$this->socialKey;
            $sKey = md5($sKey);
            $results['key'] = $sKey;

            $results['social'] = $this->utils->handleSocial($propId, $aNum, $ts, $sKey);

            if ($start) {
                $start = $start->format('Y-m-d');
                $results['start'] = $start;
            }
            // Reporting
            $results['reporting'] = $this->utils->handleReporting($propId, $end, $start);
            // Influence
            $results['influence'] = $this->utils->handleInfluence($propId, $end, $start);
            // Engagement
            $results['engagement'] = $this->utils->handleEngagement($propId, $end, $start);
            // Customers
            $results['customers'] = $this->utils->getCustomerCount($propId);
            // Internal Survey Snapshot
            $results['snapshot'] = $this->reputationSurveyHandler->getSurveySnapshot($id);
        }

        return new JsonResponse([
            'success' => true,
            'results' => $results,
        ]);
    }

    // Reviews
    public function reviewsAction(Request $request, $id)
    {
        $results = [];
        $range = $request->query->get('range', '');

        $end = new \DateTime('now');
        $end = $end->format('Y-m-d');

        $results['end'] = $end;
        $rangeResult = $this->utils->handleRange($range);
        if (!$range) {
            $results['range'] = $rangeResult['range'];
        } else {
            $results['range'] = $range;
        }
        $start = $rangeResult['start'];
        $results['start'] = $start;
        $results['reporting'] = null;
        $results['reviews'] = null;

        $property = $this->propertyRepo->findOneBy(array(
            'hash' => $id,
        ));
        if (!$property) {
            $results['account'] = null;
        } else {
            $propId = $property->getId();
            $aNum = $property->getAxNumber();
            $results['account'] = $aNum;

            if ($start) {
                $start = $start->format('Y-m-d');
                $results['start'] = $start;
            }

            // Reporting
            $results['reporting'] = $this->utils->handleReporting($propId, $end, $start);

            // Reviews
            $results['reviews'] = $this->utils->handleReviews($propId, $end, $start);

            $timestamp = new \DateTime();
            $timestamp = $timestamp->getTimeStamp();

            $sKey = $aNum.'|'.$timestamp.'|'.$this->socialKey;
            $sKey = md5($sKey);

            $results['ts'] = $timestamp;
            $results['key'] = $sKey;
            $results['social'] = $this->utils->handleSocial($propId, $aNum, $timestamp, $sKey);
        }

        return new JsonResponse([
            'success' => true,
            'results' => $results,
        ]);
    }

    // Competitors
    public function competitorsAction(Request $request, $id)
    {
        $results = [];
        $range = $request->query->get('range', '');
        $end = new \DateTime('now');
        $end = $end->format('Y-m-d');
        $results['end'] = $end;
        $rangeResult = $this->utils->handleRange($range);
        if (!$range) {
            $results['range'] = $rangeResult['range'];
        } else {
            $results['range'] = $range;
        }
        $start = $rangeResult['start'];
        $results['start'] = $start;
        $results['reporting'] = null;
        $results['competitors'] = null;

        $property = $this->propertyRepo->findOneBy(array(
            'hash' => $id,
        ));
        if (!$property) {
            $results['account'] = null;
        } else {
            $propId = $property->getId();
            $aNum = $property->getAxNumber();
            $results['account'] = $aNum;

            if ($start) {
                $start = $start->format('Y-m-d');
                $results['start'] = $start;
            }

            // Reporting
            $results['reporting'] = $this->utils->handleReporting($propId, $end, $start);

            // Competitors
            $results['competitors'] = $this->utils->handleCompetitors($propId, $end, $start);
        }

        return new JsonResponse([
            'success' => true,
            'results' => $results,
        ]);
    }

    // Customers
    public function customersAction(Request $request, $id)
    {
        $results = [];
        $results['customers'] = null;

        $property = $this->propertyRepo->findOneBy(array(
            'hash' => $id,
        ));
        if (!$property) {
            $results['account'] = null;
        } else {
            $propId = $property->getId();
            $aNum = $property->getAxNumber();
            $results['account'] = $aNum;
            // Customers
            $results['customers'] = $this->utils->getAllCustomers($propId);
        }

        return new JsonResponse([
            'success' => true,
            'results' => $results,
        ]);
    }


    // Upload Link
    public function linkAction(Request $request, $id)
    {
        $results = null;
        $property = $this->propertyRepo->findOneBy(array(
            'hash' => $id,
        ));
        if ($property) {
            $propId = $property->getId();
            $guid = $this->reputationRepo->getGuidByProperty($propId);
            if ($guid) {
                $link = $this->uploadUrl.$guid['guid'];
                //check the video url for http 200 status
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $link);
                curl_setopt($ch, CURLOPT_NOBODY, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_exec($ch);
                $is200 = curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200;
                curl_close($ch);
                if ($is200) {
                    $results = $link;
                }
            }
        }

        return new JsonResponse([
            'success' => true,
            'results' => $results,
        ]);
    }

    // Has Active Reputation
    public function activeAction(Request $request, $id)
    {
        $results = false;
        $property = $this->propertyRepo->findOneBy(array(
            'hash' => $id,
        ));
        if ($property) {
            $propId = $property->getId();
            $active = $this->reputationRepo->activeReputation($propId);
            $results = $active['active'];
        }

        return new JsonResponse([
            'success' => true,
            'results' => $results,
        ]);
    }


    // Resolve
    public function resolveAction(Request $request)
    {
        $result = null;
        $resolve = $request->request->get('resolve');
        $hash = null;

        if ($resolve) {
            foreach ($resolve as $r) {
                $review = $this->reputationReviewRepo->findOneBy(array(
                    'engageId' => $r
                ));
                if ($review) {
                    if (!$propId) {
                        $hash = $review->getReputation()->getProperty()->getHash();
                    }
                    $review->setResolved(1);
                }
            }
            $this->em->flush();
        }
        $result = $hash;

        return new JsonResponse([
            'success' => true,
            'results' => $result,
        ]);
    }
}
