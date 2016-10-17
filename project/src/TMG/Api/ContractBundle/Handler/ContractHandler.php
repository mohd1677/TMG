<?php

namespace TMG\Api\ContractBundle\Handler;

use DateTime;
use Doctrine\ORM\QueryBuilder;
use TMG\Api\ApiBundle\Entity\Repository\ContractRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use TMG\Api\ApiBundle\Handler\ApiHandler;

class ContractHandler extends ApiHandler
{
    /** @var ContractRepository $repository */
    protected $repository;

    /**
     * @param array $resolveProducts
     * @return array
     */
    public function getActiveResolveContracts(array $resolveProducts)
    {
        return $this->repository->findActiveContractsByProducts($resolveProducts);
    }

    /**
     * @return array
     */
    public function getResolveProductCodes()
    {
        return $this->repository->resolveProductCodes;
    }

    /**
     * @param DateTime $startDate
     * @param DateTime $endDate
     *
     * @return QueryBuilder
     */
    public function getActiveContractsQueryBuilder(DateTime $startDate, DateTime $endDate)
    {
        return $this->repository->findActiveContractsForDateRangeQueryBuilder($startDate, $endDate);
    }

    protected $contractRepo;
    /**
     * @return Array
     */
    public function premiumPositionList()
    {
        $this->contractRepo = $this->em->getRepository('ApiBundle:Contract');
        $premiums = [];
        $pList = $this->contractRepo->getPremiumPositionList();
        foreach ($pList as $result) {
            array_push($premiums, $result[1]);
        }

        return $premiums;
    }
}
