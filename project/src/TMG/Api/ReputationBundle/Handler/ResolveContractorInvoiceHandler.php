<?php

namespace TMG\Api\ReputationBundle\Handler;

use TMG\Api\ApiBundle\Entity\Repository\ResolveContractorInvoiceRepository;
use TMG\Api\ApiBundle\Handler\ApiHandler;
use TMG\Api\UserBundle\Entity\User;

class ResolveContractorInvoiceHandler extends ApiHandler
{
    /**
     * @var ResolveContractorInvoiceRepository $repository
     */
    protected $repository;

    /**
     * @param $contractor
     * @return array
     */
    public function getPaidByUser($contractor)
    {
        return $this->getRepository()->findBy(['user' => $contractor], ['createdAt' => 'DESC']);
    }

    /**
     * @param $contractor
     * @return mixed
     */
    public function getPaidToDateByUser($contractor)
    {
        return $this->repository->sumPaidInvoicesByUser($contractor);
    }
}
