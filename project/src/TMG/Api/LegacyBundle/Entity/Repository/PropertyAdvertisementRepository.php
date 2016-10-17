<?php

namespace TMG\Api\LegacyBundle\Entity\Repository;

use DateTime;
use Doctrine\ORM\EntityRepository;

class PropertyAdvertisementRepository extends EntityRepository
{
    /**
     * Get current advertisement
     *
     * @param string $propertyId
     *
     * @return array
     */
    public function getCurrentAdvertisementForProperty($propertyId)
    {
        $time = date("Y-m-d H:i:s", time());
        return $this->createQueryBuilder("ad")
            ->where("ad.startDate <= :time")
            ->andWhere("ad.endDate >= :time")
            ->andWhere("ad.property = :propertyId")
            ->setParameter("time", $time)
            ->setParameter("propertyId", $propertyId)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get current advertisement approved
     *
     * @param string $propertyId
     *
     * @return array
     */
    public function getAdvertisementsForProperty($propertyId)
    {
        return $this->createQueryBuilder('a')
            ->where('a.property = :property')
            ->andWhere('a.startDate <= :now')
            ->andWhere('a.endDate >= :now')
            ->andWhere('a.isApproved = 1')
            ->setParameter('property', $propertyId)
            ->setParameter('now', new DateTime('now'))
            ->orderBy('a.startDate', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
