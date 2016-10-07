<?php

namespace TMG\Api\ApiBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ResolveResponseRatingRepository
 *
 */
class ResolveResponseRatingRepository extends EntityRepository
{
    /**
     * Find users that have had at least one rated proposal
     *
     * @return array
     */
    public function findContractors()
    {
        $qb = $this->createQueryBuilder('repo');
        $qb->groupBy('repo.proposedBy');

        return $qb->getQuery()->getResult();
    }

    public function sumUnpaidInvoicesByUser($contractor)
    {
        return $this->createQueryBuilder('resolveResponseRating')
            ->select('SUM(resolveResponseRating.paymentValue)')
            ->andWhere('resolveResponseRating.proposedBy = :user')
            ->andWhere('resolveResponseRating.resolveContractorInvoice is null')
            ->setParameter('user', $contractor)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
