<?php

namespace TMG\Api\ApiBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ResolveContractorInvoiceRepository
 *
 */
class ResolveContractorInvoiceRepository extends EntityRepository
{
    /**
     * @param $contractor
     * @return mixed
     */
    public function sumPaidInvoicesByUser($contractor)
    {
        return $this->createQueryBuilder('resolveContractorInvoice')
            ->select('SUM(resolveContractorInvoice.paymentValue)')
            ->andWhere('resolveContractorInvoice.user = :user')
            ->setParameter('user', $contractor)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
