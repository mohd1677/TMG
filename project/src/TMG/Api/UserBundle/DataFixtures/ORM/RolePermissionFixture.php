<?php

namespace TMG\Api\UserBundle\DataFixtures\ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use TMG\Api\ApiBundle\DataFixtures\AbstractFixture;
use TMG\Api\UserBundle\Entity\UserRights;
use TMG\Api\UserBundle\Entity\UserRoles;

class RolePermissionFixture extends AbstractFixture
{
    /**
     * @param ObjectManager $manager
     */
    public function run(ObjectManager $manager)
    {
        $roles = $this->createRoles($manager);
        $this->createPermissions($roles, $manager);
    }

    private function createRoles(ObjectManager $manager)
    {
        $roleData = $this->loadData("role.json");

        $roles = [];

        foreach ($roleData as $key => $data) {
            $role = $this->loadRecord(['role' => $data->role], 'role');
            if (!$role) {
                $role = new UserRoles();
                $role->setRole($data->role);
                $role->setDescription($data->description);
                $role->setPlatform($data->platform);
            }

            $role->setRights(new ArrayCollection());

            $manager->persist($role);
            $roles[$key] = $role;
        }

        $manager->flush();

        return $roles;
    }

    /**
     * Create Permissions
     *
     * @param array         $roles
     * @param ObjectManager $manager
     */
    public function createPermissions(array $roles, $manager)
    {
        $permissionData = $this->loadData("permission.json");

        $createdPermissionMap = [];

        foreach ($permissionData as $role_name => $permission_list) {
            /** @var UserRoles $role */
            $role = $roles[$role_name];

            foreach ($permission_list as $data) {
                if (!isset($createdPermissionMap[$data->name])) {
                    $permission = $this->loadRecord(['name' => $data->name], 'right');

                    if (!$permission) {
                        $permission = new UserRights();
                        $permission->setName($data->name);
                        $permission->setDescription($data->description);
                    }

                    $manager->persist($permission);

                    $createdPermissionMap[$data->name] = $permission;
                }

                $permission = $createdPermissionMap[$data->name];

                $role->addRight($permission);

                $manager->persist($permission);
                $manager->persist($role);
            }
        }

        $manager->flush();
    }

    /**
     * Finds Category with name
     *
     * @param ObjectManager $manager    The Doctrine ObjectManager
     * @param string        $queryName The name of the query to return
     *
     * @return QueryBuilder
     */
    protected function getQuery(ObjectManager $manager, $queryName = null)
    {
        /** @var EntityManager $qb */
        $qb = $this->container->get('doctrine')->getEntityManager();

        if ($queryName == 'right') {
            return $qb
                ->createQueryBuilder()
                ->select('p')
                ->from('TMG\Api\UserBundle\Entity\UserRights', 'p')
                ->where('p.name = :name');
        } elseif ($queryName == 'role') {
            return $qb
                ->createQueryBuilder()
                ->select('r')
                ->from('TMG\Api\UserBundle\Entity\UserRoles', 'r')
                ->where('r.role = :role');
        }
    }
}
