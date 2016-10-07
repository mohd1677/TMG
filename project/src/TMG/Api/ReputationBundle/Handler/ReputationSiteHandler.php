<?php

namespace TMG\Api\ReputationBundle\Handler;

use TMG\Api\ApiBundle\Entity\ReputationSite;
use TMG\Api\ApiBundle\Handler\ApiHandler;

class ReputationSiteHandler extends ApiHandler
{
    public function getResolveSites()
    {
        return $this->getRepository()->findBy(['id' => ReputationSite::$resolveSites]);
    }
}
