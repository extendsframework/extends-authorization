<?php
declare(strict_types=1);

namespace ExtendsFramework\Authorization;

use ExtendsFramework\Authorization\Permission\PermissionInterface;
use ExtendsFramework\Authorization\Role\RoleInterface;

interface AuthorizationInfoInterface
{
    /**
     * Get authorization permissions.
     *
     * @return PermissionInterface[]
     */
    public function getPermissions(): array;

    /**
     * Get authorization roles.
     *
     * @return RoleInterface[]
     */
    public function getRoles(): array;
}
