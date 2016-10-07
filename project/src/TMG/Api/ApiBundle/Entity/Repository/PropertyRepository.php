<?php

namespace TMG\Api\ApiBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use TMG\Api\ApiBundle\Util\PagingInfo;

/**
 * PropertyRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PropertyRepository extends EntityRepository
{

    /**
     * Finds all properties based on criteria
     *
     * @param array $criteria
     * @param PagingInfo $pagingInfo
     * @return array
     */
    public function findForProperties(array $criteria, PagingInfo $pagingInfo)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('p')
            ->from('ApiBundle:Property', 'p');

        if (!is_null($pagingInfo->getOrder())) {
            $qb->orderBy("p.".$pagingInfo->getOrder(), $pagingInfo->getSortBy());
        }

        $qb->setParameters($criteria)
            ->setFirstResult($pagingInfo->getPage())
            ->setMaxResults($pagingInfo->getCount());

        return $qb->getQuery()->getResult();
    }

    /**
     * @param array $contracts
     * @return array
     */
    public function findActivePropertiesByContracts(array $contracts)
    {
        $queryBuilder = $this
            ->createQueryBuilder('p');

        $queryBuilder
            ->select('p')
            ->join('p.contracts', 'c')
            ->andWhere('c.id IN (:contracts)')
            ->setParameter('contracts', $contracts)
            ->orderBy('p.name')
            ->groupBy('p.hash');

        return $queryBuilder->getQuery()->getResult();
    }

    /// BELOW ARE ORIGINAL REPOSITORY METHODS WITH BAD NAMING.
    public function missingRequiredFaxList()
    {
        return $this->createQueryBuilder('p')
            ->select('p.hash as property, p.axNumber as accountNumber, p.updatedAt as reportDate')
            ->where('p.fax IS NULL')
            ->andWhere('p.sendFax = TRUE')
            ->getQuery()
            ->getResult();
    }

    public function missingRequiredFaxCount()
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->where('p.fax IS NULL')
            ->andWhere('p.sendFax = TRUE')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function missingRequiredEmailList()
    {
        return $this->createQueryBuilder('p')
            ->select('p.hash as property, p.axNumber as accountNumber, p.updatedAt as reportDate')
            ->where('p.email IS NULL')
            ->andWhere('p.sendEmail = TRUE')
            ->getQuery()
            ->getResult();
    }

    public function missingRequiredEmailCount()
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->where('p.email IS NULL')
            ->andWhere('p.sendEmail = TRUE')
            ->getQuery()
            ->getSingleScalarResult();
    }
}