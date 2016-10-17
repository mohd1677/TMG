<?php

namespace TMG\Api\ReputationBundle\Handler;

use TMG\Api\ApiBundle\Handler\ApiHandler;

class ReputationHandler extends ApiHandler
{
    /**
     * @param $id
     * @return mixed
     */
    public function getReputationById($id)
    {
        return $this->getRepository()->find($id);
    }

    /**
     * @param $data
     */
    public function createReportingData($data)
    {
        $this->class->createReportingData($data);
    }
}
