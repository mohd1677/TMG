<?php

namespace TMG\Api\ApiBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ResolveSettingSiteRepository
 *
 */
class ResolveSettingSiteRepository extends EntityRepository
{
    /**
     * @param $resolveSetting
     */
    public function deleteAllByResolveSetting($resolveSetting)
    {
        $queryBuilder = $this->createQueryBuilder('resolveSettingSite');
        $queryBuilder->delete()
            ->where('resolveSettingSite.resolveSetting = :resolveSetting')
            ->setParameter('resolveSetting', $resolveSetting);
        $query = $queryBuilder->getQuery();
        $query->getResult();
    }
}
