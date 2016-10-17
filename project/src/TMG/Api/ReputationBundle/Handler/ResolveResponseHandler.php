<?php

namespace TMG\Api\ReputationBundle\Handler;

use TMG\Api\ApiBundle\Entity\Repository\ResolveResponseRepository;
use TMG\Api\ApiBundle\Entity\ResolveResponse;
use TMG\Api\ApiBundle\Handler\ApiHandler;
use TMG\Api\ApiBundle\Util\PagingInfo;

class ResolveResponseHandler extends ApiHandler
{
    /**
     * @var ResolveResponseRepository $repository
     */
    protected $repository;

    public function getLastActivityByUser($user)
    {
        $return = [];

        $pagingInfo = new PagingInfo();
        $pagingInfo->setCount(1);

        $recentActivities = $this->repository->findByMostRecentActivity($pagingInfo, $user);

        foreach ($recentActivities as $recentActivity) {
            if ($recentActivity instanceof ResolveResponse) {
                $return = $recentActivity;
            }
        }

        return $return;
    }

    public function getProposalCountByUser($user)
    {
        return count($this->repository->findProposalsByUser($user));
    }

    /**
     * @param $resolveResponseCollection
     * @return array
     */
    public function stringifyResolveResponseIndices($resolveResponseCollection)
    {
        $resolveResponses = [];
        /** @var ResolveResponse $resolveResponse */
        foreach ($resolveResponseCollection as $resolveResponse) {
            $resolveResponses[$resolveResponse->getAction()] = $resolveResponse;
        }

        return $resolveResponses;
    }

    /**
     * @return array
     */
    public function getContractors()
    {
        $contractors = [];
        $contractorList = $this->repository->findContractors();

        /** @var ResolveResponse $contractor */
        foreach ($contractorList as $contractor) {
            $contractors[] = $contractor->getUser();
        }

        return $contractors;
    }
}
