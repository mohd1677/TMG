<?php

namespace TMG\Api\LegacyBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class ZipCodeRepository extends EntityRepository
{

    /**
     * Find place having distance less than 50
     *
     * @param string $lat
     * @param string $long
     *
     * @return array
     */
    public function findClosestCityState($lat, $long)
    {
        $result = $this->createQueryBuilder('z')
            ->select(
                "z.zip, z.city, z.state, z.latitude, z.longitude,
                ( 3959 * acos( cos( radians(:lat) ) * cos( radians( z.latitude ) )
                * cos( radians( z.longitude ) - radians(:long) ) + sin( radians(:lat) )
                * sin(radians(z.latitude)) ) ) AS distance"
            )
            ->having('distance < 50')
            ->setParameters(["lat" => $lat, "long" =>$long])
            ->orderBy('distance')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();

        return $result;
    }
}
