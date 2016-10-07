<?php

namespace TMG\Api\ApiBundle\Handler;

use TMG\Api\UserBundle\Entity\Repository\UserRightsRepository;
use TMG\Api\UserBundle\Entity\User;
use TMG\Api\UserBundle\Entity\UserRights;

class SecurityHandler extends ApiHandler
{
    /**
     * @var UserRightsRepository
     */
    protected $repository;

    /**
     * Checks a permission against a users allowed permissions.
     *
     * @param User|null $user
     * @param null $permission
     * @return bool
     */
    public function canUser(User $user = null, $permission = null)
    {
        $roles = [User::ANONYMOUS];
        if ($user) {
            $roles = $user->getRoles();
        }

        if (in_array(User::ROLE_SUPER_ADMIN, $roles)) {
            return true;
        }

        if (!$this->repository->findByRoleAndPermission($permission, $roles)) {
            return false;
        }

        return true;
    }
}
