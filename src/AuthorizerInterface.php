<?php
declare(strict_types=1);

namespace ExtendsFramework\Authorization;

use ExtendsFramework\Authorization\Permission\PermissionInterface;
use ExtendsFramework\Authorization\Policy\PolicyInterface;
use ExtendsFramework\Authorization\Role\RoleInterface;
use ExtendsFramework\Identity\IdentityInterface;

interface AuthorizerInterface
{
    /**
     * If identity is permitted for permission.
     *
     * @param IdentityInterface $identity
     * @param PermissionInterface $permission
     *
     * @return bool
     */
    public function isPermitted(IdentityInterface $identity, PermissionInterface $permission): bool;

    /**
     * If identity has role.
     *
     * @param IdentityInterface $identity
     * @param RoleInterface $role
     *
     * @return bool
     */
    public function hasRole(IdentityInterface $identity, RoleInterface $role): bool;

    /**
     * If identity is allowed by policy.
     *
     * @param IdentityInterface $identity
     * @param PolicyInterface $policy
     *
     * @return bool
     */
    public function isAllowed(IdentityInterface $identity, PolicyInterface $policy): bool;
}
