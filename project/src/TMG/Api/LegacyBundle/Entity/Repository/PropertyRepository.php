<?php
namespace TMG\Api\LegacyBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use TMG\Api\LegacyBundle\Entity\User;

class PropertyRepository extends EntityRepository
{
    private $videoCodes = ['HTCVIDM', '6HTCVIDM', 'HTCPHOTO'];
    private $socialCodes = ['MYSOCIAL', 'MYSOCIALPRO', '6MYSOCIAL', '6MYSOCIALPRO'];
    private $reputationCodes = ['WDPRSA', '6WDPRSA', 'WDPRBT', '6WDPRBT'];
    private $callStatisticsCodes = ['HTCIT800', 'TMG800', 'HTCIT800R', 'TMG800R', '6TMG800', '6TMG800R'];

    /**
     * check if the first identifier/primary key field has $needle
     *
     * @param string $needle
     *
     * @return bool
     */
    public function exists($needle)
    {
        $pk = $this->getClassMetadata()->getIdentifier()[0];

        return $this->createQueryBuilder('t0')
            ->select("COUNT(t0.$pk)")
            ->where("t0.$pk = :val")
            ->getQuery()
            ->setParameters(['val' => $needle])
            ->getSingleScalarResult() > 0;
    }

    /**
     * QueryBuilder for property and looks like the $short should be letter p
     *
     * @param string $short
     * @param string $indexBy
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder($short, $indexBy = null)
    {
        return parent::createQueryBuilder($short)
            ->leftJoin("$short.description", "{$short}d")
            ->addSelect("partial pd.{id}");
    }

    /**
     * Gets Properties for property search.
     *
     * @param User $user
     * @param array $search
     *
     * @return Query
     */
    public function searchProperties($user, $search = [])
    {
        $findBy = [
            'p.name',
            'p.accountNumber',
            'p.id',
            'p.phone',
            'p.axAccountNumber',
            'p.e1AccountNumber',
            'p.smsNumber'
        ];

        $itemCodes = []; //search $search for itemCode groups we need to search for
        if (array_key_exists('hasVideo', $search) && isset($search['hasVideo'])) {
            $itemCodes[] = $this->videoCodes;
        }

        if (array_key_exists('hasSocial', $search) && isset($search['hasSocial'])) {
            $itemCodes[] = $this->socialCodes;
        }

        if (array_key_exists('hasReputation', $search) && isset($search['hasReputation'])) {
            $itemCodes[] = $this->reputationCodes;
        }

        if (array_key_exists('hasCallStatistics', $search) && isset($search['hasCallStatistics'])) {
            $itemCodes[] = $this->callStatisticsCodes;
        }

        $qb = $this->getPropertiesWithCurrentContracts($itemCodes, true);

        // Hoteliers and clerks get their search limited to their own
        // properties.
        $role = $user->getRoles()[0];
        $orStatement = '';
        $searchTerm = '';
        if (isset($search['propertySearch'])) {
            $searchTerm = $search['propertySearch'];
        }

        if ($searchTerm != null && $searchTerm != '') {
            $count = 0;

            foreach ($findBy as $field) {
                if ($count == 0) {
                    $orStatement .= "$field LIKE :search";
                } else {
                    $orStatement .= " OR $field LIKE :search";
                }
                $count++;
            }

            $qb->andWhere($orStatement)
                ->setParameter('search', "%$searchTerm%");
        }

        //only return allowed
        if ($role == 'HOTELIER' || $role == 'CLERK') {
            $allowed = $user->getProperties()
                ->map(function ($p) {
                    return $p->getId();
                })
                ->toArray();

            $qb->andWhere('p.id IN (:allowedIds)')
                ->setParameter('allowedIds', $allowed);
        }

        return $qb->getQuery();
    }

    /**
     *
     * @param array $contractCodes
     * @param bool $returnQb
     *
     * @return array|QueryBuilder
     */
    public function getPropertiesWithCurrentContracts(array $contractCodes = [], $returnQb = false)
    {
        /** @var \Doctrine\ORM\QueryBuilder $qb */
        $qb = $this->createQueryBuilder('p');

        // If we were given an array of contract codes to narrow it down to, let's do that.
        if ($contractCodes) {
            $count = 0;
            $qb->setParameter('inviableCollectionMessages', ['CA'])
                ->setParameter('now', new \DateTime('now'));

            foreach ($contractCodes as $group) {
                $qb->join('p.axContracts', "c$count")
                    ->andWhere("c$count.startDate <= :now")
                    ->andWhere("c$count.endDate > :now")
                    ->andWhere(
                        $qb->expr()->orX(
                            "c$count.collectionMessage IS NULL",
                            "c$count.collectionMessage NOT IN (:inviableCollectionMessages)"
                        )
                    )
                    ->andWhere("c$count.itemCode IN (:contractCodes$count)")
                    ->setParameter("contractCodes$count", $group);

                $count++;
            }
        }

        if ($returnQb) {
            return $qb;
        }

        return $qb->getQuery()->getResult();
    }
}
