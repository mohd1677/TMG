<?php

namespace TMG\Api\UserBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use TMG\Api\UserBundle\Entity\UserRights;

/**
 * UserRightsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRightsRepository extends EntityRepository
{
    /**
     * Checks if user has a role with the provided permission
     *
     * @param  String $permission
     * @param  array  $roles
     *
     * @return UserRights
     */
    public function findByRoleAndPermission($permission, array $roles)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select('p, r')
                ->from('TMG\Api\UserBundle\Entity\UserRights', 'p')
                ->join('p.roles', 'r')
                ->where('p.name = :permission')
                ->andWhere("r.role  IN(:roles)")
                ->setParameter('permission', $permission)
                ->setParameter('roles', $roles);

      //  \Doctrine\Common\Util\Debug::dump($qb->getQuery()->getResult()); exit;

        return $qb->getQuery()->getResult();
    }
}
