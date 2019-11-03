<?php
declare(strict_types=1);

namespace ExtendsFramework\Authorization;

use ExtendsFramework\Authorization\Permission\PermissionInterface;
use ExtendsFramework\Authorization\Role\RoleInterface;

class AuthorizationInfo implements AuthorizationInfoInterface
{
    /**
     * Authorization info permissions.
     *
     * @var PermissionInterface[]
     */
    private $permissions = [];

    /**
     * Authorization info $roles.
     *
     * @var RoleInterface[]
     */
    private $roles = [];

    /**
     * @inheritDoc
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * @inheritDoc
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * Add $permission to authorization info.
     *
     * @param PermissionInterface $permission
     * @return AuthorizationInfo
     */
    public function addPermission(PermissionInterface $permission): AuthorizationInfo
    {
        $this->permissions[] = $permission;

        return $this;
    }

    /**
     * Add $role to authorization info.
     *
     * @param RoleInterface $role
     * @return AuthorizationInfo
     */
    public function addRole(RoleInterface $role): AuthorizationInfo
    {
        $this->roles[] = $role;

        return $this;
    }
}
