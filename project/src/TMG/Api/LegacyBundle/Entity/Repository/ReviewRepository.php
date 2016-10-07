<?php

namespace TMG\Api\LegacyBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class ReviewRepository extends EntityRepository
{

    /**
     * All reviews by date range
     *
     * @param string $accountNumber
     * @param string $startDate
     * @param string $endDate
     *
     * @return array
     */
    public function getAllReviewsByDateRange($accountNumber, $startDate, $endDate)
    {
        return $this->createQueryBuilder('rv')
            ->select('rv')
            ->where('rv.submittedAt >= :startDate AND rv.submittedAt <= :endDate AND rv.accountNumber = :accountNumber')
            ->setParameters(['startDate' => $startDate, 'endDate' => $endDate, 'accountNumber' => $accountNumber])
            ->getQuery()
            ->getResult();
    }

    /**
     * Reviews
     *
     * @param string $accountNumber
     * @param string $startDate
     * @param string $endDate
     * @param integer $page
     * @param string $reviewType
     * @param string $source
     *
     * @return array
     */
    public function getReviewsPageable($accountNumber, $startDate, $endDate, $page, $reviewType, $source)
    {
        $offset = (int)$page * 30;
        $limit = 30;

        if ($source == 'all') {
            $w = 'rv.submittedAt >= :startDate
                AND rv.submittedAt <= :endDate
                AND rv.accountNumber = :accountNumber
                AND rv.reviewType = :reviewType';
            $p = [
                'startDate' => $startDate,
                'endDate' => $endDate,
                'accountNumber' => $accountNumber,
                'reviewType' => $reviewType
            ];
        } else {
            $w = 'rv.submittedAt >= :startDate
                AND rv.submittedAt <= :endDate
                AND rv.accountNumber = :accountNumber
                AND rv.reviewType = :reviewType
                AND rv.source = :source';

            $p = [
                'startDate' => $startDate,
                'endDate' => $endDate,
                'accountNumber' => $accountNumber,
                'reviewType' => $reviewType,
                'source' => urldecode($source)
            ];
        }

        return $this->createQueryBuilder('rv')
            ->select('rv')
            ->where($w)
            ->orderBy('rv.submittedAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->setParameters($p)
            ->getQuery()
            ->getResult();
    }

    /**
     * Review Sources
     *
     * @param string $accountNumber
     *
     * @return array
     */
    public function getReviewSources($accountNumber)
    {
        //select distinct source from reputation_reviews where account_number = 13187100 and review_type = 'external';
        return $this->createQueryBuilder('rv')
            ->select('DISTINCT rv.source')
            ->where("rv.accountNumber = :num and rv.reviewType = 'external'")
            ->setParameters(['num' => $accountNumber])
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $accountNumber
     *
     * @return array
     */
    public function getCompetitorAccountNumbers($accountNumber)
    {
        return $this->createQueryBuilder('rv')
            ->select('rv')
            ->where('rv.accountNumber = :num AND rv.reviewType = :type')
            ->setParameters(['num' => $accountNumber, 'type' => 'external'])
            ->getQuery()
            ->getResult();
    }

    /**
     * Competitors
     *
     * @param string $accountNumber
     *
     * @return array
     */
    public function getCompetitorList($accountNumber)
    {
        return $this->createQueryBuilder('rv')
            ->select('DISTINCT rv.account')
            ->where("rv.accountNumber = :num and rv.reviewType = 'competitor'")
            ->setParameters(['num' => $accountNumber])
            ->getQuery()
            ->getResult();
    }

    /**
     * Reviews
     *
     * @param integer $page
     * @param string $accountNumber
     * @param string $account
     * @param string $startDate
     * @param string $endDate
     *
     * @return array
     */
    public function getCompetitorReviews($page, $accountNumber, $account, $startDate, $endDate)
    {
        $offset = (int)$page * 30;
        $limit = 30;
        $qb = $this->createQueryBuilder('rv');

        $qb->select('rv')
            ->where('rv.submittedAt >= :startDate
                AND rv.submittedAt <= :endDate
                AND rv.accountNumber = :accountNumber
                AND rv.account = :account')
            ->orderBy('rv.submittedAt', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->setParameters([
                'accountNumber' => $accountNumber,
                'account' => $account,
                'startDate' => $startDate,
                'endDate' => $endDate
            ]);

        return $qb->getQuery()->execute();
    }
}
