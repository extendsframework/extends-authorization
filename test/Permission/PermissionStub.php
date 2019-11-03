<?php
declare(strict_types=1);

namespace ExtendsFramework\Authorization\Permission;

class PermissionStub implements PermissionInterface
{
    /**
     * @inheritDoc
     */
    public function implies(PermissionInterface $permission): bool
    {
        return true;
    }
}
