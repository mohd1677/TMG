<?php

namespace TMG\Api\ReputationBundle\Handler;

use DateTime;
use TMG\Api\ApiBundle\Entity\Repository\ReputationEmailRepository;
use TMG\Api\ApiBundle\Entity\Reputation;
use TMG\Api\ApiBundle\Handler\ApiHandler;

class ReputationEmailHandler extends ApiHandler
{
    /** @var  ReputationEmailRepository $repository */
    protected $repository;

    /**
     * @param Reputation $reputation
     * @param DateTime $start
     * @param DateTime $end
     * @return int
     */
    public function getTotalSent(Reputation $reputation, DateTime $start, DateTime $end)
    {
        return $this->repository->findByReputationAndDate($reputation, $start, $end);
    }
}
