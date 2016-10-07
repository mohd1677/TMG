<?php

namespace TMG\Api\LegacyBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use TMG\Api\LegacyBundle\Entity\Property;

class AnalyticRepository extends EntityRepository
{
    /**
     * @param Property $property
     *
     * @return array
     */
    public function getLast30Days($property)
    {
        $timestamp = date("Y-m-d", strtotime("-1 month"));
        return $this->createQueryBuilder('a')
            ->select('a')
            ->where("a.property = :property AND a.reportDate >= '$timestamp'")
            ->setParameters(['property' => $property->getId()])
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Property $property
     *
     * @return array
     */
    public function getAnalyticsByMonthForYear($property)
    {
        $results = [];
        for ($i = 0; $i < 12; $i++) {
            $timestamp = date("Y-m", strtotime("-$i month"));
            $n = $i - 1;
            $endDate = date("Y-m", strtotime("-$n month"));
            $results[] = $this->createQueryBuilder('a')
                ->select('a')
                ->where("a.property = :property AND a.reportDate >= '$timestamp' AND a.reportDate <= '$endDate'")
                ->setParameters(['property' => $property->getId()])
                ->getQuery()
                ->getResult();
        }

        return $results;
    }

    /**
     * @param Property $property
     * @param $startDate
     * @param $endDate
     *
     * @return array
     */
    public function getAnalyticLog($property, $startDate, $endDate)
    {
        return $this->createQueryBuilder('a')
            ->select('a')
            ->where("a.property = :id AND a.reportDate >= :startDate AND a.reportDate <= :endDate")
            ->orderBy('a.reportDate', 'DESC')
            ->setParameters(['id' => $property->getId(), 'startDate' => $startDate, 'endDate' => $endDate])
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Property $property
     * @param $startDate
     * @param $endDate
     *
     * @return array
     */
    public function getAnalyticExportLog($property, $startDate, $endDate)
    {
        $limit = 1000;
        return $this->createQueryBuilder('a')
            ->select('a')
            ->where("a.property = :id AND a.reportDate >= :startDate AND a.reportDate <= :endDate")
            ->orderBy('a.reportDate', 'DESC')
            ->setMaxResults($limit)
            ->setParameters(['id' => $property->getId(), 'startDate' => $startDate, 'endDate' => $endDate])
            ->getQuery()
            ->getResult();
    }

    /**
     * @return string
     */
    public function getLatestDate()
    {
        return $this->createQueryBuilder('a')
            ->select('max(a.reportDate)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
