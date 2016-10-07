<?php

namespace TMG\Api\ReputationBundle\Handler;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use TMG\Api\ApiBundle\Entity\Repository\ReputationReviewRepository;
use TMG\Api\ApiBundle\Entity\Reputation;
use TMG\Api\ApiBundle\Entity\ReputationReview;
use TMG\Api\ApiBundle\Entity\ReputationSite;
use TMG\Api\ApiBundle\Handler\ApiHandler;
use TMG\Api\UserBundle\Entity\User;

/**
 * Class ReputationReviewHandler
 * @package TMG\Api\ReputationBundle\Handler
 *
 * @property ReputationReviewRepository $repository
 */
class ReputationReviewHandler extends ApiHandler
{
    /**
     * @var ReputationReviewRepository
     */
    protected $repository;

    /**
     * @param Reputation|null $reputation
     * @param int|array|ArrayCollection|null $sites
     * @param User|null $user
     * @param DateTime|null $start
     * @param DateTime|null $end
     * @return int
     */
    public function getSlaCritical(
        Reputation $reputation = null,
        $sites = null,
        User $user = null,
        DateTime $start = null,
        DateTime $end = null
    ) {
        return $this->repository->findByResolveStatus(
            'sla_critical',
            $reputation,
            $sites,
            $user,
            $start,
            $end
        )->getQuery()->getResult();
    }

    /**
     * @param Reputation|null $reputation
     * @param int|array|ArrayCollection|null $sites
     * @param User|null $user
     * @param DateTime|null $start
     * @param DateTime|null $end
     * @return int
     */
    public function getSlaNormal(
        Reputation $reputation = null,
        $sites = null,
        User $user = null,
        DateTime $start = null,
        DateTime $end = null
    ) {
        return $this->repository->findByResolveStatus(
            'sla_normal',
            $reputation,
            $sites,
            $user,
            $start,
            $end
        )->getQuery()->getResult();
    }

    /**
     * @param Reputation|null $reputation
     * @param int|array|ArrayCollection|null $sites
     * @param User|null $user
     * @param DateTime|null $start
     * @param DateTime|null $end
     * @return int
     */
    public function getAll(
        Reputation $reputation = null,
        $sites = null,
        User $user = null,
        DateTime $start = null,
        DateTime $end = null
    ) {
        return $this->repository->findByResolveStatus(
            'all',
            $reputation,
            $sites,
            $user,
            $start,
            $end
        )->getQuery()->getResult();
    }

    /**
     * @param Reputation|null $reputation
     * @param int|array|ArrayCollection|null $sites
     * @param User|null $user
     * @param DateTime|null $start
     * @param DateTime|null $end
     * @return int
     */
    public function getPendingResponse(
        Reputation $reputation = null,
        $sites = null,
        User $user = null,
        DateTime $start = null,
        DateTime $end = null
    ) {
        return $this->repository->findByResolveStatus(
            'pending_response',
            $reputation,
            $sites,
            $user,
            $start,
            $end
        )->getQuery()->getResult();
    }

    /**
     * @param Reputation|null $reputation
     * @param int|array|ArrayCollection|null $sites
     * @param User|null $user
     * @param DateTime|null $start
     * @param DateTime|null $end
     * @return int
     */
    public function getPendingApproval(
        Reputation $reputation = null,
        $sites = null,
        User $user = null,
        DateTime $start = null,
        DateTime $end = null
    ) {
        return $this->repository->findByResolveStatus(
            'pending_approval',
            $reputation,
            $sites,
            $user,
            $start,
            $end
        )->getQuery()->getResult();
    }

    /**
     * @param Reputation|null $reputation
     * @param int|array|ArrayCollection|null $sites
     * @param User|null $user
     * @param DateTime|null $start
     * @param DateTime|null $end
     * @return int
     */
    public function getPendingResolve(
        Reputation $reputation = null,
        $sites = null,
        User $user = null,
        DateTime $start = null,
        DateTime $end = null
    ) {
        return $this->repository->findByResolveStatus(
            'pending_resolve',
            $reputation,
            $sites,
            $user,
            $start,
            $end
        )->getQuery()->getResult();
    }

    /**
     * @param Reputation|null $reputation
     * @param int|array|ArrayCollection|null $sites
     * @param User|null $user
     * @param DateTime|null $start
     * @param DateTime|null $end
     * @return int
     */
    public function getCompletedForAnalyst(
        Reputation $reputation = null,
        $sites = null,
        User $user = null,
        DateTime $start = null,
        DateTime $end = null
    ) {
        return $this->repository->findByResolveStatus(
            'analyst_completed',
            $reputation,
            $sites,
            $user,
            $start,
            $end
        )->getQuery()->getResult();
    }

    /**
     * @param Reputation|null $reputation
     * @param int|array|ArrayCollection|null $sites
     * @param User|null $user
     * @param DateTime|null $start
     * @param DateTime|null $end
     * @return int
     */
    public function getPendingForContractor(
        Reputation $reputation = null,
        $sites = null,
        User $user = null,
        DateTime $start = null,
        DateTime $end = null
    ) {
        return $this->repository->findByResolveStatus(
            'contractor_pending',
            $reputation,
            $sites,
            $user,
            $start,
            $end
        )->getQuery()->getResult();
    }

    /**
     * @param Reputation|null $reputation
     * @param int|array|ArrayCollection|null $sites
     * @param User|null $user
     * @param DateTime|null $start
     * @param DateTime|null $end
     * @return int
     */
    public function getProposed(
        Reputation $reputation = null,
        $sites = null,
        User $user = null,
        DateTime $start = null,
        DateTime $end = null
    ) {
        return $this->repository->findByResolveStatus(
            'proposed',
            $reputation,
            $sites,
            $user,
            $start,
            $end
        )->getQuery()->getResult();
    }

    /**
     * @param Reputation|null $reputation
     * @param int|array|ArrayCollection|null $sites
     * @param User|null $user
     * @param DateTime|null $start
     * @param DateTime|null $end
     * @return int
     */
    public function getUnpaid(
        Reputation $reputation = null,
        $sites = null,
        User $user = null,
        DateTime $start = null,
        DateTime $end = null
    ) {
        return $this->repository->findByResolveStatus(
            'unpaid',
            $reputation,
            $sites,
            $user,
            $start,
            $end
        )->getQuery()->getResult();
    }

    /**
     * @param Reputation|null $reputation
     * @param int|array|ArrayCollection|null $sites
     * @param User|null $user
     * @param DateTime|null $start
     * @param DateTime|null $end
     * @return int
     */
    public function getCompletedForContractor(
        Reputation $reputation = null,
        $sites = null,
        User $user = null,
        DateTime $start = null,
        DateTime $end = null
    ) {
        return $this->repository->findByResolveStatus(
            'contractor_completed',
            $reputation,
            $sites,
            $user,
            $start,
            $end
        )->getQuery()->getResult();
    }

    /**
     * @param Reputation|null $reputation
     * @param int|array|ArrayCollection|null $sites
     * @param User|null $user
     * @param DateTime|null $start
     * @param DateTime|null $end
     * @return int
     */
    public function countSlaCritical(
        Reputation $reputation = null,
        $sites = null,
        User $user = null,
        DateTime $start = null,
        DateTime $end = null
    ) {
        return $this->repository->findByResolveStatus(
            'sla_critical',
            $reputation,
            $sites,
            $user,
            $start,
            $end
        )->select('COUNT(ReputationReviewRepository.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param Reputation|null $reputation
     * @param int|array|ArrayCollection|null $sites
     * @param User|null $user
     * @param DateTime|null $start
     * @param DateTime|null $end
     * @return int
     */
    public function countSlaNormal(
        Reputation $reputation = null,
        $sites = null,
        User $user = null,
        DateTime $start = null,
        DateTime $end = null
    ) {
        return $this->repository->findByResolveStatus(
            'sla_normal',
            $reputation,
            $sites,
            $user,
            $start,
            $end
        )->select('COUNT(ReputationReviewRepository.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param Reputation|null $reputation
     * @param int|array|ArrayCollection|null $sites
     * @param User|null $user
     * @param DateTime|null $start
     * @param DateTime|null $end
     * @return int
     */
    public function countAll(
        Reputation $reputation = null,
        $sites = null,
        User $user = null,
        DateTime $start = null,
        DateTime $end = null
    ) {
        return $this->repository->findByResolveStatus(
            'all',
            $reputation,
            $sites,
            $user,
            $start,
            $end
        )->select('COUNT(ReputationReviewRepository.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param Reputation|null $reputation
     * @param int|array|ArrayCollection|null $sites
     * @param User|null $user
     * @param DateTime|null $start
     * @param DateTime|null $end
     * @return int
     */
    public function countPendingResponse(
        Reputation $reputation = null,
        $sites = null,
        User $user = null,
        DateTime $start = null,
        DateTime $end = null
    ) {
        return $this->repository->findByResolveStatus(
            'pending_response',
            $reputation,
            $sites,
            $user,
            $start,
            $end
        )->select('COUNT(ReputationReviewRepository.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param Reputation|null $reputation
     * @param int|array|ArrayCollection|null $sites
     * @param User|null $user
     * @param DateTime|null $start
     * @param DateTime|null $end
     * @return int
     */
    public function countPendingApproval(
        Reputation $reputation = null,
        $sites = null,
        User $user = null,
        DateTime $start = null,
        DateTime $end = null
    ) {
        return $this->repository->findByResolveStatus(
            'pending_approval',
            $reputation,
            $sites,
            $user,
            $start,
            $end
        )->select('COUNT(ReputationReviewRepository.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param Reputation|null $reputation
     * @param int|array|ArrayCollection|null $sites
     * @param User|null $user
     * @param DateTime|null $start
     * @param DateTime|null $end
     * @return int
     */
    public function countPendingResolve(
        Reputation $reputation = null,
        $sites = null,
        User $user = null,
        DateTime $start = null,
        DateTime $end = null
    ) {
        return $this->repository->findByResolveStatus(
            'pending_resolve',
            $reputation,
            $sites,
            $user,
            $start,
            $end
        )->select('COUNT(ReputationReviewRepository.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param Reputation|null $reputation
     * @param int|array|ArrayCollection|null $sites
     * @param User|null $user
     * @param DateTime|null $start
     * @param DateTime|null $end
     * @return int
     */
    public function countCompletedForAnalyst(
        Reputation $reputation = null,
        $sites = null,
        User $user = null,
        DateTime $start = null,
        DateTime $end = null
    ) {
        return $this->repository->findByResolveStatus(
            'analyst_completed',
            $reputation,
            $sites,
            $user,
            $start,
            $end
        )->select('COUNT(ReputationReviewRepository.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param Reputation|null $reputation
     * @param int|array|ArrayCollection|null $sites
     * @param User|null $user
     * @param DateTime|null $start
     * @param DateTime|null $end
     * @return int
     */
    public function countPendingForContractor(
        Reputation $reputation = null,
        $sites = null,
        User $user = null,
        DateTime $start = null,
        DateTime $end = null
    ) {
        return $this->repository->findByResolveStatus(
            'contractor_pending',
            $reputation,
            $sites,
            $user,
            $start,
            $end
        )->select('COUNT(ReputationReviewRepository.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param Reputation|null $reputation
     * @param int|array|ArrayCollection|null $sites
     * @param User|null $user
     * @param DateTime|null $start
     * @param DateTime|null $end
     * @return int
     */
    public function countProposed(
        Reputation $reputation = null,
        $sites = null,
        User $user = null,
        DateTime $start = null,
        DateTime $end = null
    ) {
        return $this->repository->findByResolveStatus(
            'proposed',
            $reputation,
            $sites,
            $user,
            $start,
            $end
        )->select('COUNT(ReputationReviewRepository.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param Reputation|null $reputation
     * @param int|array|ArrayCollection|null $sites
     * @param User|null $user
     * @param DateTime|null $start
     * @param DateTime|null $end
     * @return int
     */
    public function countUnpaid(
        Reputation $reputation = null,
        $sites = null,
        User $user = null,
        DateTime $start = null,
        DateTime $end = null
    ) {
        return $this->repository->findByResolveStatus(
            'unpaid',
            $reputation,
            $sites,
            $user,
            $start,
            $end
        )->select('COUNT(ReputationReviewRepository.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param Reputation|null $reputation
     * @param int|array|ArrayCollection|null $sites
     * @param User|null $user
     * @param DateTime|null $start
     * @param DateTime|null $end
     * @return int
     */
    public function sumUnpaid(
        Reputation $reputation = null,
        $sites = null,
        User $user = null,
        DateTime $start = null,
        DateTime $end = null
    ) {
        return $this->repository->findByResolveStatus(
            'unpaid',
            $reputation,
            $sites,
            $user,
            $start,
            $end
        )->select('SUM(ResolveResponseRating.paymentValue)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param Reputation|null $reputation
     * @param int|array|ArrayCollection|null $sites
     * @param User|null $user
     * @param DateTime|null $start
     * @param DateTime|null $end
     * @return int
     */
    public function countCompletedForContractor(
        Reputation $reputation = null,
        $sites = null,
        User $user = null,
        DateTime $start = null,
        DateTime $end = null
    ) {
        return $this->repository->findByResolveStatus(
            'contractor_completed',
            $reputation,
            $sites,
            $user,
            $start,
            $end
        )->select('COUNT(ReputationReviewRepository.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param Reputation $reputation
     * @param ReputationSite $reputationSite
     * @param bool $resolvable
     * @param DateTime|null $effectiveAt
     */
    public function setResolvable(
        Reputation $reputation,
        ReputationSite $reputationSite,
        $resolvable = true,
        $effectiveAt = null
    ) {
        $this->repository->updateResolvable($reputation, $reputationSite, $resolvable, $effectiveAt);
    }

    /**
     * @param Reputation $reputation
     * @param DateTime $start
     * @param DateTime $end
     * @param array $reputationSites
     * @return array
     */
    public function getByReputation(
        Reputation $reputation,
        DateTime $start,
        DateTime $end,
        array $reputationSites
    ) {
        $sites = [];

        /** @var ReputationSite $reputationSite */
        foreach ($reputationSites as $reputationSite) {
            $sites[] = $reputationSite->getId();
        }

        return $this->repository->findByReputation($reputation, $start, $end, $sites);
    }

    /**
     * @param string $pending response|approval|resolve|completed
     * @param Reputation $reputation
     * @param DateTime $start
     * @param DateTime $end
     * @param array $reputationSites
     * @param null|string $range
     * @return array
     */
    public function getPendingResponseByReputation(
        $pending,
        Reputation $reputation,
        DateTime $start,
        DateTime $end,
        array $reputationSites,
        $range = null
    ) {
        $sites = [];

        /** @var ReputationSite $reputationSite */
        foreach ($reputationSites as $reputationSite) {
            $sites[] = $reputationSite->getId();
        }

        switch ($pending) {
            default:
            case 'response':
                return $this->repository->findPendingResponseByReputation($reputation, $start, $end, $range);
                break;

            case 'approval':
                return $this->repository->findPendingApprovalByReputation($reputation, $start, $end, $range);
                break;

            case 'resolve':
                return $this->repository->findPendingResolveByReputation($reputation, $start, $end, $range);
                break;

            case 'completed':
                return $this->repository->findPendingCompletedByReputation($reputation, $start, $end, $range);
                break;
        }
    }

    /**
     * @param $user
     * @param $engageId
     * @param null|ReputationSite $site
     * @return array|ReputationReview
     */
    public function getReviewForResponse($user, $engageId, $site)
    {
        $this->clearResponseReservations();

        if ($engageId) {
            /** @var ReputationReview $review */
            $review = $this->repository->findOneBy(
                [
                    'engageId' => $engageId,
                    'respondedAt' => null,
                ]
            );
        } else {
            /** @var ReputationReview $review */
            $review = $this->repository->findOneByResponseReserved($user);

            if (!$review instanceof ReputationReview) {
                $review = $this->repository->findOneByOldestPendingResponse($site);
            }

            if ($review instanceof ReputationReview) {
                $start = new DateTime(date('Y-m-1 00:00:00', $review->getPostDate()->getTimestamp()));
                $end = new DateTime(date('Y-m-t 23:59:59', $review->getPostDate()->getTimestamp()));

                /**
                 * we have a review, but we need to check if the total number of reviews already
                 * in the pipeline for this month meets or exceeds the SLA for this property
                 * and if so we will remove this review from the contractor queue and get the next one
                 */
                if ($this->countCompletedForAnalyst($review->getReputation(), null, $user, $start, $end)
                    + $this->countPendingResolve($review->getReputation(), null, $user, $start, $end)
                    + $this->countPendingApproval($review->getReputation(), null, $user, $start, $end)
                    + $this->countProposed($review->getReputation(), null, $user, $start, $end)
                    >= $review->getReputation()->getProperty()->getResolveSetting()->getSlaNormal()
                    + $review->getReputation()->getProperty()->getResolveSetting()->getSlaCritical()
                ) {
                    $review->setProposable(false);
                    $review->setReservedBy(null);
                    $review->setReservedAt(null);
                    $this->save($review);
                    $review = $this->getReviewForResponse($user, $engageId, $site);
                }
            }
        }

        if ($review instanceof ReputationReview) {
            $review->setReservedBy($user);
            $review->setReservedAt(new DateTime());
            $this->save($review);
        }

        return $review;
    }

    /**
     * @return array
     */
    public function clearResponseReservations()
    {
        $reviews = $this->repository->findByExpiredResponseReservedAt();

        foreach ($reviews as $review) {
            /** @var ReputationReview $review */
            $review->setReservedAt();
            $review->setReservedBy();
            $this->save($review);
        }

        return $reviews;
    }
}
