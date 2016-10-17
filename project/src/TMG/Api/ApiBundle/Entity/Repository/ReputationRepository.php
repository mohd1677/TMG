<?php

namespace TMG\Api\ApiBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class ReputationRepository
 *
 * @package TMG\Api\ApiBundle\Entity\Repository
 */
class ReputationRepository extends EntityRepository
{
    public function getTotalsByProperty($id)
    {
        return $this->createQueryBuilder('r')
            ->select(
                'r.externalAverageRating as avg_rating',
                'r.externalTotal as reviews',
                'r.externalPositive as positive',
                'r.tripAdvisorRating as trip_rating',
                'r.tripAdvisorRank as trip_rank'
            )
            ->where('r.property = :id')
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    public function getInfluenceTotalsByProperty($id)
    {
        return $this->createQueryBuilder('r')
            ->select(
                'r.lifetimeSent as sent',
                'r.totalCustomers as customers',
                'r.lastUpload as last_upload',
                'r.lifetimeYes as yes_clicks',
                'r.lifetimeNo as no_clicks'
            )
            ->where('r.property = :id')
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    public function getGuidByProperty($id)
    {
        return $this->createQueryBuilder('r')
            ->select('r.guid')
            ->where('r.property = :id')
            ->andWhere('r.guid IS NOT NULL')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function activeReputation($id)
    {
        return $this->createQueryBuilder('r')
            ->select('r.active')
            ->where('r.property = :id')
            ->andWhere('r.active IS NOT NULL')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getActiveReputationAccounts()
    {
        $contractRepository = $this
            ->getEntityManager()
            ->getRepository('ApiBundle:Contract');

        return $this->createQueryBuilder('reputation')
            ->select('reputation')
            ->join('reputation.property', 'property')
            ->join('property.contracts', 'contract')
            ->join('contract.product', 'product')
            ->where('product.code IN (:reputationProductCodes)')
            ->andWhere('reputation.active = true')
            ->setParameter('reputationProductCodes', $contractRepository->reputationProductCodes)
            ->getQuery()
            ->getResult();
    }

    public function getActiveResolveAccounts()
    {
        $contractRepository = $this
            ->getEntityManager()
            ->getRepository('ApiBundle:Contract');

        return $this->createQueryBuilder('reputation')
            ->select('reputation')
            ->join('reputation.property', 'property')
            ->join('property.contracts', 'contract')
            ->join('contract.product', 'product')
            ->where('product.code IN (:resolveProductCodes)')
            ->andWhere('reputation.active = true')
            ->setParameter('resolveProductCodes', $contractRepository->resolveProductCodes)
            ->getQuery()
            ->getResult();
    }
}
