<?php

namespace TMG\Api\ReputationBundle\Handler;

use TMG\Api\ApiBundle\Entity\ResolveResponseRating;
use TMG\Api\ApiBundle\Entity\Repository\ResolveResponseRatingRepository;
use TMG\Api\ApiBundle\Handler\ApiHandler;
use TMG\Api\UserBundle\Entity\User;

class ResolveResponseRatingHandler extends ApiHandler
{
    /**
     * @var ResolveResponseRatingRepository $repository
     */
    protected $repository;

    /**
     * Find users that have had at least one rated proposal
     *
     * @return array
     */
    public function getContractors()
    {
        $contractors = [];
        $contractorList = $this->repository->findContractors();

        /** @var ResolveResponseRating $contractor */
        foreach ($contractorList as $contractor) {
            $contractors[] = $contractor->getProposedBy();
        }

        return $contractors;
    }

    public function getBalanceToDateByUser($contractor)
    {
        return $this->repository->sumUnpaidInvoicesByUser($contractor);
    }

    /**
     * @param User $user
     * @return array
     */
    public function getUnpaidByUser(User $user)
    {
        return $this->getRepository()->findBy(
            [
                'proposedBy' => $user,
                'resolveContractorInvoice' => null,
            ]
        );
    }

    /**
     * @param User $user
     * @param int $rating
     * @return int
     */
    public function calculatePaymentValue(User $user, $rating)
    {
        $paymentValue = 0;

        if ($user->getContractorPayScale() == 3) {
            //level 3 is a special level that can only be set or unset by management
            $paymentValue = ResolveResponseRating::$rateByLevel[$user->getContractorPayScale()];
        } elseif ($rating > 1) {
            $ratings = $this->getRepository()->findBy(
                ['proposedBy' => $user],
                ['createdAt' => 'desc'],
                30
            );

            //must have at least 30 reviews for a chance to bump up rate level
            if (count($ratings) < 30) {
                $user->setContractorPayScale(1);
            } else {
                $ratingAverage = $this->computeRatingAverage($ratings);

                switch ($user->getContractorPayScale()) {
                    case 1:
                        //if they have an 80% or better average after 30 reviews
                        if ($ratingAverage >= 4) {
                            $user->setContractorPayScale(2);
                        }
                        break;

                    case 2:
                        //if they slip back below 75% after being bumped up
                        if ($ratingAverage < 3.75) {
                            $user->setContractorPayScale(1);
                        }
                        break;
                }
            };

            $paymentValue = ResolveResponseRating::$rateByLevel[$user->getContractorPayScale()];
        }

        return $paymentValue;
    }

    /**
     * @param array $ratings
     * @return float|int
     */
    public function computeRatingAverage(array $ratings)
    {
        $count = 0;
        $sum = 0;

        /** @var ResolveResponseRating $rating */
        foreach ($ratings as $rating) {
            $count++;
            $sum += $rating->getRating();
        }

        return $count ? $sum / $count : 0;
    }
}
