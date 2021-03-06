<?php

namespace TMG\Api\ApiBundle\Entity\Repository;

/**
 * ReputationCompetitorRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReputationCompetitorRepository extends \Doctrine\ORM\EntityRepository
{

    public function getTotalsByProperty($id)
    {
        $qb = $this->createQueryBuilder('rc')
            ->select(
                'rc.lifetimeReviews AS reviews',
                'rc.lifetimeRating AS rating',
                'rc.name'
            )
            ->join('rc.reputation', 'r')
            ->where('r.property = :id')
            ->andWhere('rc.lifetimeReviews IS NOT NULL')
            ->andWhere('rc.lifetimeRating IS NOT NULL')
            ->setParameter('id', $id);

        return $qb->getQuery()
            ->getResult();
    }

    public function findByProperty($id)
    {
        $qb = $this->createQueryBuilder('rc')
            ->select('rc')
            ->join('rc.reputation', 'r')
            ->where('r.property = :id')
            ->setParameter('id', $id);

        return $qb->getQuery()
            ->getResult();
    }
}
