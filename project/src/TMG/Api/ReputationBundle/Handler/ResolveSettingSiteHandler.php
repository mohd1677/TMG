<?php

namespace TMG\Api\ReputationBundle\Handler;

use TMG\Api\ApiBundle\Entity\Repository\ResolveSettingSiteRepository;
use TMG\Api\ApiBundle\Entity\ResolveSetting;
use TMG\Api\ApiBundle\Entity\ResolveSettingSite;
use TMG\Api\ApiBundle\Handler\ApiHandler;

class ResolveSettingSiteHandler extends ApiHandler
{
    /** @var  ResolveSettingSiteRepository $repository */
    protected $repository;

    /**
     * @param ResolveSetting $resolveSetting
     */
    public function deleteAllByResolveSetting(ResolveSetting $resolveSetting)
    {
        $this->repository->deleteAllByResolveSetting($resolveSetting);
    }

    public function getSitesByResolveSetting(ResolveSetting $resolveSetting)
    {
        $sites = [];

        $resolveSettingSites = $this->getRepository()->findBy(['resolveSetting' => $resolveSetting]);

        /** @var ResolveSettingSite $resolveSettingSite */
        foreach ($resolveSettingSites as $resolveSettingSite) {
            $sites[] = $resolveSettingSite->getReputationSite();
        }

        return $sites;
    }
}
