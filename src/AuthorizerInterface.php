<?php
declare(strict_types=1);

namespace ExtendsFramework\Authorization;

use ExtendsFramework\Authorization\Permission\PermissionInterface;
use ExtendsFramework\Authorization\Role\RoleInterface;
use ExtendsFramework\Identity\IdentityInterface;

interface AuthorizerInterface
{
    /**
     * Verify if $identity is permitted for $permission.
     *
     * @param IdentityInterface   $identity
     * @param PermissionInterface $permission
     * @return bool
     */
    public function isPermitted(IdentityInterface $identity, PermissionInterface $permission): bool;

    /**
     * Verify if $identity has $role.
     *
     * @param IdentityInterface $identity
     * @param RoleInterface     $role
     * @return bool
     */
    public function hasRole(IdentityInterface $identity, RoleInterface $role): bool;
}
