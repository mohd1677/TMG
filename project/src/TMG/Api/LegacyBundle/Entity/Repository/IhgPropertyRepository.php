<?php

namespace TMG\Api\LegacyBundle\Entity\Repository;

use DateTime;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\EntityRepository;

class IhgPropertyRepository extends EntityRepository
{

    /**
     * Viable listings
     *
     * @return array
     */
    public function getViableListings()
    {
        $queryBuilder = $this
            ->createQueryBuilder('i')
            ->leftJoin(
                'MatrixBundle:Property',
                'p',
                Join::WITH,
                'i.hotelCode = p.ihgProperty'
            )
            ->where('i.endDate >= :now')
            ->andWhere('i.slug IS NOT NULL')
            ->setParameter('now', new DateTime('now'));

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Hotels with code not in $hotelCodes
     *
     * @param array $hotelCodes
     *
     * @return array
     */
    public function findWhereHotelCodeNotIn(array $hotelCodes)
    {
        $queryBuilder = $this->createQueryBuilder('ihgProperty');

        return $queryBuilder
            ->where(
                $queryBuilder->expr()->notIn(
                    'ihgProperty.hotelCode',
                    $hotelCodes
                )
            )
            ->getQuery()
            ->getResult();
    }
}
