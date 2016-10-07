<?php

namespace TMG\Api\ApiBundle\Entity\Repository;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use TMG\Api\ApiBundle\Util\PagingInfo;
use TMG\Api\UserBundle\Entity\User;

/**
 * ResolveResponseRepository
 *
 */
class ResolveResponseRepository extends EntityRepository
{
    /**
     * @param User $user
     * @return \Doctrine\Common\Collections\Collection
     */
    public function findProposalsByUser(User $user)
    {
        $expr = Criteria::expr();
        $criteria = Criteria::create();
        $criteria->where(
            $expr->andX(
                $expr->eq('user', $user),
                $expr->eq('action', 'propose')
            )
        );

        return $this->matching($criteria)->toArray();
    }

    public function findByMostRecentActivity(PagingInfo $pagingInfo, User $user)
    {
        $expr = Criteria::expr();
        $criteria = Criteria::create();
        $criteria->where(
            $expr->andX(
                $expr->eq('user', $user)
            )
        );
        $criteria->orderBy(['createdAt' => 'DESC']);

        if ($pagingInfo->getCount()) {
            $criteria->setMaxResults($pagingInfo->getCount());
        }

        return $this->matching($criteria)->toArray();
    }

    public function findContractors()
    {
        $queryBuilder = $this->createQueryBuilder('repo');
        $queryBuilder
            ->where('repo.role = :role')
            ->groupBy('repo.user');

        return $queryBuilder
            ->setParameter('role', 'contractor')
            ->getQuery()
            ->getResult();
    }
}
