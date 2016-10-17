<?php

namespace TMG\Api\ApiBundle\Entity\Repository;

use DateTime;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use TMG\Api\UserBundle\Entity\User;
use TMG\Api\ApiBundle\Entity\Reputation;
use Doctrine\Common\Collections\Criteria;
use TMG\Api\ApiBundle\Entity\ReputationSite;
use TMG\Api\ApiBundle\Entity\ResolveResponse;
use TMG\Api\ApiBundle\Entity\ReputationReview;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ReputationReviewRepository
 *
 */
class ReputationReviewRepository extends EntityRepository
{
    /**
     * @param string|null $status
     * @param Reputation|null $reputation
     * @param int|array|ArrayCollection|null $sites
     * @param User|null $user
     * @param DateTime|null $start
     * @param DateTime|null $end
     *
     * @return QueryBuilder
     */
    public function findByResolveStatus(
        $status,
        Reputation $reputation = null,
        $sites = null,
        User $user = null,
        DateTime $start = null,
        DateTime $end = null
    ) {
        $userField = null;
        $queryBuilder = $this->createQueryBuilder('ReputationReviewRepository');

        switch ($status) {
            case 'sla_critical':
                $queryBuilder->andWhere('ReputationReviewRepository.critical = 1')
                    ->andWhere('ReputationReviewRepository.resolvedAt is not null');
                break;

            case 'sla_normal':
                $queryBuilder->andWhere('ReputationReviewRepository.critical = 0')
                    ->andWhere('ReputationReviewRepository.resolvedAt is not null');
                break;

            case 'all':
                $queryBuilder->andWhere('ReputationReviewRepository.resolvable = 1');
                break;

            case 'pending_response':
                $queryBuilder->andWhere('ReputationReviewRepository.resolvable = 1')
                    ->andWhere('ReputationReviewRepository.respondedAt is null');
                break;

            case 'pending_approval':
                $queryBuilder->andWhere('ReputationReviewRepository.respondedAt is not null')
                    ->andWhere('ReputationReviewRepository.approvedAt is null');
                //if ($user) {
                //    $userField = 'respondedBy';
                //}
                break;

            case 'pending_resolve':
                $queryBuilder->andWhere('ReputationReviewRepository.approvedAt is not null')
                    ->andWhere('ReputationReviewRepository.resolvedAt is null');
                //if ($user) {
                //    $userField = 'approvedBy';
                //}
                break;

            case 'analyst_completed':
                $queryBuilder->andWhere('ReputationReviewRepository.resolvedAt is not null');
                //if ($user) {
                //    $userField = 'resolvedBy';
                //}
                break;

            case 'contractor_pending':
                $queryBuilder
                    ->andWhere('ReputationReviewRepository.proposable = 1')
                    ->andWhere('ReputationReviewRepository.resolvable = 1')
                    ->andWhere('ReputationReviewRepository.proposedAt is null')
                    ->andWhere('ReputationReviewRepository.respondedAt is null')
                    ->andWhere('ReputationReviewRepository.reservedAt is null');
                if ($user) {
                    $userField = 'reservedBy';
                }
                break;

            case 'proposed':
                $queryBuilder->andWhere('ReputationReviewRepository.proposedAt is not null')
                    ->andWhere('ReputationReviewRepository.respondedAt is null');
                if ($user) {
                    $userField = 'reservedBy';
                }
                break;

            case 'unpaid':
                $queryBuilder->andWhere('ReputationReviewRepository.proposedAt is not null')
                    ->join('ReputationReviewRepository.resolveResponse', 'ResolveResponse')
                    ->join('ResolveResponse.resolveResponseRating', 'ResolveResponseRating')
                    ->andWhere('ReputationReviewRepository.respondedAt is not null')
                    ->andWhere('ResolveResponseRating.resolveContractorInvoice is null');
                if ($user) {
                    $userField = 'reservedBy';
                }
                break;

            case 'contractor_completed':
                $queryBuilder->andWhere('ReputationReviewRepository.proposedAt is not null')
                    ->andWhere('ReputationReviewRepository.respondedAt is not null')
                    ->join('ReputationReviewRepository.resolveResponse', 'ResolveResponse')
                    ->join('ResolveResponse.resolveResponseRating', 'ResolveResponseRating')
                    ->andWhere('ResolveResponseRating.resolveContractorInvoice is not null');
                if ($user) {
                    $userField = 'reservedBy';
                }
                break;
        }

        if ($reputation) {
            $queryBuilder->andWhere('ReputationReviewRepository.reputation = :reputation');
            $queryBuilder->setParameter('reputation', $reputation);
        }

        if ($sites) {
            $queryBuilder->andWhere('ReputationReviewRepository.site IN (:sites)');
            $queryBuilder->setParameter('sites', $sites);
        }

        if ($start) {
            $queryBuilder->andWhere('ReputationReviewRepository.postDate >= :start');
            $queryBuilder->setParameter('start', $start);
        }

        if ($end) {
            $queryBuilder->andWhere('ReputationReviewRepository.postDate <= :end');
            $queryBuilder->setParameter('end', $end);
        }

        if ($userField) {
            $queryBuilder->andWhere('ReputationReviewRepository.'.$userField.' = :user');
            $queryBuilder->setParameter('user', $user);
        }

        return $queryBuilder;
    }

    /**
     * @param Reputation $reputation
     * @param ReputationSite $reputationSite
     * @param bool $resolvable
     * @param null|DateTime $effectiveAt
     */
    public function updateResolvable(
        Reputation $reputation,
        ReputationSite $reputationSite,
        $resolvable = true,
        $effectiveAt = null
    ) {
        if ($effectiveAt == null) {
            $effectiveAt = new DateTime(ResolveResponse::LAUNCH_DATE);
        }

        $queryBuilder = $this->createQueryBuilder('ReputationReview')
            ->update()
            ->set('ReputationReview.resolvable', $resolvable === true ? 1 : 0)
            ->andWhere('ReputationReview.reputation = :reputation')
            ->andWhere('ReputationReview.site = :reputationSite')
            ->andWhere('ReputationReview.postDate >= :effectiveAt')
            ->setParameter('reputation', $reputation)
            ->setParameter('reputationSite', $reputationSite)
            ->setParameter('effectiveAt', $effectiveAt);

        if ($resolvable) {
            //do not mark generic content as resolvable
            $queryBuilder->andWhere('ReputationReview.content not in (:genericContent)')
                ->setParameter('genericContent', ReputationReview::$genericContent);
        } else {
            //do not mark anything that has been worked on as not resolvable
            $queryBuilder->andWhere('ReputationReview.reservedAt is null')
                ->andWhere('ReputationReview.respondedAt is null');
        }

        $queryBuilder->getQuery()->execute();
    }

    public function getReviewsByProperty($id, $start)
    {
        $qb = $this->createQueryBuilder('rr')
            ->select(
                'rr.engageId',
                'rr.postDate',
                'rr.username',
                'rr.contentShort',
                'rr.content',
                'rr.contentUrl',
                'rr.tone',
                'rr.sentiment',
                'rr.resolvedAt',
                's.name AS siteName'
            )
            ->join('rr.reputation', 'r')
            ->join('rr.site', 's')
            ->where('r.property = :id')
            ->setParameter('id', $id);

        if ($start) {
            $yrmo = new DateTime($start);
            $yrmo = (int)$yrmo->format('ym');

            $qb->andWhere('rr.yrmo >= :yrmo')
                ->andWhere('rr.yrmo IS NOT NULL')
                ->setParameter('yrmo', $yrmo);
        }

        return $qb->orderBy('rr.postDate', 'DESC')
            ->getQuery()
            ->getResult();

    }

    public function getSitesByProperty($id, $start)
    {
        $sites = [];
        $qb = $this->createQueryBuilder('rr')
            ->select('DISTINCT(s.name)')
            ->join('rr.reputation', 'r')
            ->join('rr.site', 's')
            ->where('r.property = :id')
            ->setParameter('id', $id);

        if ($start) {
            $yrmo = new DateTime($start);
            $yrmo = (int)$yrmo->format('ym');

            $qb->andWhere('rr.yrmo >= :yrmo')
                ->andWhere('rr.yrmo IS NOT NULL')
                ->setParameter('yrmo', $yrmo);
        }

        $results = $qb->getQuery()
            ->getResult();

        if ($results) {
            foreach ($results as $r) {
                array_push($sites, $r[1]);
            }
        } else {
            $sites = null;
        }

        return $sites;
    }

    public function getSiteBreakdownByProperty($reputationId, $site)
    {
        $breakdown['positive'] = $this->createQueryBuilder('tad')
            ->select('COUNT(tad.id)')
            ->join('tad.reputation', 'r')
            ->where('r.property = :reputationId')
            ->andWhere('tad.site = :site')
            ->andWhere('tad.tone >= 4')
            ->setParameter('reputationId', $reputationId)
            ->setParameter('site', $site)
            ->getQuery()
            ->getSingleScalarResult();

        $breakdown['negative'] = $this->createQueryBuilder('tad')
            ->select('COUNT(tad.id)')
            ->join('tad.reputation', 'r')
            ->where('r.property = :reputationId')
            ->andWhere('tad.site = :site')
            ->andWhere('tad.tone <= 2')
            ->setParameter('reputationId', $reputationId)
            ->setParameter('site', $site)
            ->getQuery()
            ->getSingleScalarResult();

        return $breakdown;
    }

    /**
     * @param $user
     * @param $start
     * @param $end
     *
     * @return array
     */
    public function findByResponseProposedApproved($user, $start, $end)
    {
        $expr = Criteria::expr();
        $criteria = Criteria::create();
        $criteria->where(
            $expr->andX(
                $expr->eq('reservedBy', $user),
                $expr->gte('proposedAt', $start),
                $expr->lte('proposedAt', $end),
                $expr->neq('respondedAt', null)
            )
        );
        $criteria->orderBy(['respondedAt' => 'ASC']);

        return $this->matching($criteria)->toArray();
    }

    /**
     * @param $user
     * @param $start
     * @param $end
     *
     * @return array
     */
    public function findByResponseProposedWaitingApproval($user, $start, $end)
    {
        $expr = Criteria::expr();
        $criteria = Criteria::create();
        $criteria->where(
            $expr->andX(
                $expr->gte('proposedAt', $start),
                $expr->lte('proposedAt', $end),
                $expr->eq('respondedAt', null),
                $expr->eq('reservedBy', $user)
            )
        );
        $criteria->orderBy(['proposedAt' => 'ASC']);

        return $this->matching($criteria)->toArray();
    }

    /**
     * @param null|ReputationSite $site
     *
     * @return array|ReputationReview
     */
    public function findOneByOldestPendingResponse($site)
    {
        $return = [];

        $reviews = $this->findByOldestPendingResponse($site, $maxResults = 1);

        foreach ($reviews as $review) {
            if ($review instanceof ReputationReview) {
                $return = $review;
            }
        }

        return $return;
    }

    /**
     * @param null|ReputationSite $site
     * @param int $maxResults
     *
     * @return array|ReputationReview
     */
    public function findByOldestPendingResponse($site, $maxResults = 0)
    {
        $expr = Criteria::expr();
        $criteria = Criteria::create();
        $criteria->where(
            $expr->andX(
                $expr->eq('proposable', 1),
                $expr->eq('resolvable', 1),
                $expr->eq('proposedAt', null),
                $expr->eq('respondedAt', null),
                $expr->eq('reservedAt', null)
            )
        );
        if ($site) {
            $criteria->andWhere($expr->eq('site', $site));
        }
        $criteria->orderBy(['postDate' => 'ASC']);

        if ($maxResults) {
            $criteria->setMaxResults($maxResults);
        }

        return $this->matching($criteria)->toArray();
    }

    /**
     * @ApiDoc(
     *    section = "ResolveResponse",
     *    resource = false,
     *    description = "find a review reserved for response by a user",
     * )
     * @param User $user
     *
     * @return array
     */
    public function findOneByResponseReserved(User $user)
    {
        $return = [];

        $expr = Criteria::expr();
        $criteria = Criteria::create();
        $criteria->where(
            $expr->andX(
                $expr->eq('reservedBy', $user),
                $expr->eq('proposedAt', null),
                $expr->eq('respondedAt', null)
            )
        );
        $criteria->setMaxResults(1);

        $reviews = $this->matching($criteria)->toArray();

        foreach ($reviews as $review) {
            if ($review instanceof ReputationReview) {
                $return = $review;
            }
        }

        return $return;
    }

    public function findByExpiredResponseReservedAt()
    {
        $expr = Criteria::expr();
        $criteria = Criteria::create();
        $criteria->where(
            $expr->andX(
                $expr->eq('respondedAt', null),
                $expr->eq('proposedAt', null),
                $expr->lte('reservedAt', new DateTime('-1 hour'))
            )
        );

        return $this->matching($criteria)->toArray();
    }

    /**
     * @ApiDoc(
     *    section = "ResolveResponse",
     *    resource = false,
     *    description = "By reputation, find the number of reviews collected",
     * )
     *
     * @param Reputation $reputation
     * @param DateTime $start
     * @param DateTime $end
     * @param array $sites
     *
     * @return mixed
     */
    public function findByReputation(
        Reputation $reputation,
        DateTime $start,
        DateTime $end,
        array $sites
    ) {
        $expr = Criteria::expr();
        $criteria = Criteria::create();
        $criteria->where(
            $expr->andX(
                $expr->in('site', $sites),
                $expr->eq('reputation', $reputation),
                $expr->gte('postDate', $start),
                $expr->lte('postDate', $end),
                $expr->orX(
                    $expr->eq('resolvable', true),
                    $expr->neq('respondedAt', null)
                )
            )
        );

        $reviews = $this->matching($criteria)->toArray();

        return $reviews;
    }

    /**
     * @ApiDoc(
     *    section = "ResolveResponse",
     *    resource = false,
     *    description = "By reputation, find the number of completed reviews not marked critical",
     * )
     *
     * @param Reputation $reputation
     * @param DateTime $start
     * @param DateTime $end
     *
     * @return mixed
     */
    public function findSlaByReputation(Reputation $reputation, DateTime $start, DateTime $end)
    {
        $expr = Criteria::expr();
        $criteria = Criteria::create();
        $criteria->where(
            $expr->andX(
                $expr->eq('reputation', $reputation),
                $expr->orX(
                    $expr->eq('critical', null),
                    $expr->eq('critical', false)
                ),
                $expr->neq('resolvedAt', null),
                $expr->gte('postDate', $start),
                $expr->lte('postDate', $end)
            )
        );
        $reviews = $this->matching($criteria)->toArray();

        return $reviews;
    }

    /**
     * @ApiDoc(
     *    section = "ResolveResponse",
     *    resource = false,
     *    description = "By reputation, find the number of completed reviews marked critical",
     * )
     *
     * @param Reputation $reputation
     * @param DateTime $start
     * @param DateTime $end
     *
     * @return mixed
     */
    public function findSlaPlusByReputation(Reputation $reputation, DateTime $start, DateTime $end)
    {
        $expr = Criteria::expr();
        $criteria = Criteria::create();
        $criteria->where(
            $expr->andX(
                $expr->eq('reputation', $reputation),
                $expr->eq('critical', true),
                $expr->neq('resolvedAt', null),
                $expr->gte('postDate', $start),
                $expr->lte('postDate', $end)
            )
        );
        $reviews = $this->matching($criteria)->toArray();

        return $reviews;
    }

    /**
     * @param Reputation $reputation
     * @param DateTime $start
     * @param DateTime $end
     * @param null|string $range null|low|mid|high
     *
     * @return array
     */
    public function findPendingResponseByReputation(
        Reputation $reputation,
        DateTime $start,
        DateTime $end,
        $range
    ) {
        $expr = Criteria::expr();
        $criteria = Criteria::create();
        $criteria->where(
            $expr->andX(
                $expr->eq('reputation', $reputation),
                $expr->eq('respondedAt', null),
                $expr->gte('postDate', $start),
                $expr->lte('postDate', $end),
                $expr->orX(
                    $expr->eq('resolvable', true),
                    $expr->neq('taggedAt', null),
                    $expr->neq('proposedAt', null)
                )
            )
        );
        if ($range) {
            switch ($range) {
                case 'low':
                    $criteria->andWhere($expr->lt('tone', 3));
                    break;
                case 'mid':
                    $criteria->andWhere($expr->eq('tone', 3));
                    break;
                case 'high':
                    $criteria->andWhere($expr->gt('tone', 3));
                    break;
            }
        }
        $criteria->orderBy(['tone' => 'ASC', 'postDate' => 'ASC']);

        return $this->matching($criteria)->toArray();
    }

    /**
     * @param Reputation $reputation
     * @param DateTime $start
     * @param DateTime $end
     * @param null|string $range null|low|mid|high
     *
     * @return array
     */
    public function findPendingApprovalByReputation(
        Reputation $reputation,
        DateTime $start,
        DateTime $end,
        $range = null
    ) {
        $expr = Criteria::expr();
        $criteria = Criteria::create();
        $criteria->where(
            $expr->andX(
                $expr->eq('reputation', $reputation),
                $expr->neq('respondedAt', null),
                $expr->eq('approvedAt', null),
                $expr->gte('postDate', $start),
                $expr->lte('postDate', $end)
            )
        );
        if ($range) {
            switch ($range) {
                case 'low':
                    $criteria->andWhere($expr->lt('tone', 3));
                    break;
                case 'mid':
                    $criteria->andWhere($expr->eq('tone', 3));
                    break;
                case 'high':
                    $criteria->andWhere($expr->gt('tone', 3));
                    break;
            }
        }
        $criteria->orderBy(['tone' => 'ASC', 'postDate' => 'ASC']);

        return $this->matching($criteria)->toArray();
    }

    /**
     * @param Reputation $reputation
     * @param DateTime $start
     * @param DateTime $end
     * @param null|string low|mid|high $range
     *
     * @return array
     */
    public function findPendingResolveByReputation(
        Reputation $reputation,
        DateTime $start,
        DateTime $end,
        $range = null
    ) {
        $expr = Criteria::expr();
        $criteria = Criteria::create();
        $criteria->where(
            $expr->andX(
                $expr->eq('reputation', $reputation),
                $expr->neq('approvedAt', null),
                $expr->eq('resolvedAt', null),
                $expr->gte('postDate', $start),
                $expr->lte('postDate', $end)
            )
        );
        if ($range) {
            switch ($range) {
                case 'low':
                    $criteria->andWhere($expr->lt('tone', 3));
                    break;
                case 'mid':
                    $criteria->andWhere($expr->eq('tone', 3));
                    break;
                case 'high':
                    $criteria->andWhere($expr->gt('tone', 3));
                    break;
            }
        }
        $criteria->orderBy(['tone' => 'ASC', 'postDate' => 'ASC']);

        return $this->matching($criteria)->toArray();
    }

    /**
     * @param Reputation $reputation
     * @param DateTime $start
     * @param DateTime $end
     * @param null|string low|mid|high $range
     *
     * @return array
     */
    public function findPendingCompletedByReputation(
        Reputation $reputation,
        DateTime $start,
        DateTime $end,
        $range = null
    ) {
        $expr = Criteria::expr();
        $criteria = Criteria::create();
        $criteria->where(
            $expr->andX(
                $expr->eq('reputation', $reputation),
                $expr->neq('resolvedAt', null),
                $expr->gte('postDate', $start),
                $expr->lte('postDate', $end)
            )
        );
        if ($range) {
            switch ($range) {
                case 'low':
                    $criteria->andWhere($expr->lt('tone', 3));
                    break;
                case 'mid':
                    $criteria->andWhere($expr->eq('tone', 3));
                    break;
                case 'high':
                    $criteria->andWhere($expr->gt('tone', 3));
                    break;
            }
        }
        $criteria->orderBy(['tone' => 'ASC', 'postDate' => 'ASC']);

        return $this->matching($criteria)->toArray();
    }

    /**
     * Get the monthly average rating for a given date
     *
     * @param  Reputation $reputation
     * @param  DateTime $date
     *
     * @return string
     */
    public function getExternalAvgRating(Reputation $reputation, DateTime $date)
    {
        // We need to exclude FourSquare from the results because they don't
        // do user ratings, only reviews.
        $foursquare = $this
            ->getEntityManager()
            ->getRepository('ApiBundle:ReputationSite')
            ->findOneBy(['name' => 'FourSquare']);

        return $this->createQueryBuilder('r')
            ->select('AVG(r.tone) as tone')
            ->where('r.reputation = :reputation')
            ->andWhere('r.site != :foursquare')
            ->andWhere('r.yrmo = :yrmo')
            ->setParameter('reputation', $reputation)
            ->setParameter('foursquare', $foursquare)
            ->setParameter('yrmo', $date->format('ym'))
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Get the monthly average TripAdvisor rating for a given date
     *
     * @param  Reputation $reputation
     * @param  DateTime $date
     *
     * @return string
     */
    public function getTripAdvisorAvgRating(Reputation $reputation, DateTime $date)
    {
        // We only want to include TripAdvisor in this average.
        $tripAdvisor = $this
            ->getEntityManager()
            ->getRepository('ApiBundle:ReputationSite')
            ->findOneBy(['name' => 'TripAdvisor']);

        return $this->createQueryBuilder('r')
            ->select('AVG(r.tone) as tone')
            ->where('r.reputation = :reputation')
            ->andWhere('r.site = :tripadvisor')
            ->andWhere('r.yrmo = :yrmo')
            ->setParameter('reputation', $reputation)
            ->setParameter('tripadvisor', $tripAdvisor)
            ->setParameter('yrmo', $date->format('ym'))
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getMonthlySiteData(Reputation $reputation, DateTime $date)
    {
        return $this->createQueryBuilder('r')
            ->select(
                's.name AS siteName',
                'COUNT(s.name) as siteCount',
                'AVG(r.tone) as siteTone'
            )
            ->join('r.site', 's')
            ->where('r.reputation = :reputation')
            ->andWhere('r.yrmo = :yrmo')
            ->setParameter('reputation', $reputation)
            ->setParameter('yrmo', $date->format('ym'))
            ->groupBy('siteName')
            ->getQuery()
            ->getResult();

    }
}
