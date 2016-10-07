<?php

namespace TMG\Api\ApiBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * SocialDataRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SocialDataRepository extends EntityRepository
{
    public function getSocialTypesByProperty($propertyId)
    {
        return $this->createQueryBuilder('sd')
            ->select('DISTINCT(t.id)', 't.name', 't.type')
            ->join('sd.social', 's')
            ->join('sd.type', 't')
            ->where('s.property = :id')
            ->setParameter('id', $propertyId)
            ->getQuery()
            ->getResult();
    }
}