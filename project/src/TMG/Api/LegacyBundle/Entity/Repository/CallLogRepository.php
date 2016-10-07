<?php
namespace TMG\Api\LegacyBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class CallLogRepository extends EntityRepository
{

    /**
     * Last 30 days call log
     *
     * @param string $propertyId
     *
     * @return array
     */
    public function getLast30DaysOfCalls($propertyId)
    {
        $timestamp = date("Y-m-d", strtotime("-1 month"));

        return $this->createQueryBuilder('cl')
            ->select('cl')
            ->where('cl.property = :id AND cl.startTime >= :timestamp')
            ->setParameters(['id' => $propertyId, 'timestamp' => $timestamp])
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $propertyId
     * @param string $startDate
     * @param string $endDate
     *
     * @return array
     */
    public function getCallsByDateRange($propertyId, $startDate, $endDate)
    {
        $startDate = \DateTime::createFromFormat('Y-m-d', $startDate);

        $endDate = \DateTime::createFromFormat('Y-m-d', $endDate);
        $endDate->setTime(23, 59, 59);

        return $this->createQueryBuilder('cl')
            ->select('cl')
            ->where('cl.property = :id AND cl.startTime >= :startDate AND cl.startTime <= :endDate')
            ->orderBy('cl.startTime', 'DESC')
            ->setParameters(['id' => $propertyId, 'startDate' => $startDate, 'endDate' => $endDate])
            ->getQuery()
            ->getResult();
    }

    /**
     * gets pageable call log
     *
     * @param string $propertyId
     * @param integer $page
     * @param string $startDate
     * @param string $endDate
     *
     * @return array|null
     */
    public function getCallLog($propertyId, $page, $startDate, $endDate)
    {
        $startDate = \DateTime::createFromFormat('Y-m-d', $startDate);

        $endDate = \DateTime::createFromFormat('Y-m-d', $endDate);
        $endDate->setTime(23, 59, 59);

        $offset = (int)$page * 30;
        $limit = 30;

        return $this->createQueryBuilder('cl')
            ->select('cl')
            ->where('cl.property = :id AND cl.startTime >= :startDate AND cl.startTime <= :endDate')
            ->orderBy('cl.startTime', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->setParameters(['id' => $propertyId, 'startDate' => $startDate, 'endDate' => $endDate])
            ->getQuery()
            ->getResult();
    }
}
