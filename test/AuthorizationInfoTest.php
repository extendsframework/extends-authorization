<?php
declare(strict_types=1);

namespace ExtendsFramework\Authorization;

use ExtendsFramework\Authorization\Permission\PermissionInterface;
use ExtendsFramework\Authorization\Role\RoleInterface;
use PHPUnit\Framework\TestCase;

class AuthorizationInfoTest extends TestCase
{
    /**
     * Get methods.
     *
     * Test that correct values will be returned.
     *
     * @covers \ExtendsFramework\Authorization\AuthorizationInfo::addPermission()
     * @covers \ExtendsFramework\Authorization\AuthorizationInfo::addRole()
     * @covers \ExtendsFramework\Authorization\AuthorizationInfo::getPermissions()
     * @covers \ExtendsFramework\Authorization\AuthorizationInfo::getRoles()
     */
    public function testGetMethods(): void
    {
        $permission = $this->createMock(PermissionInterface::class);

        $role = $this->createMock(RoleInterface::class);

        /**
         * @var PermissionInterface $permission
         * @var RoleInterface       $role
         */
        $info = new AuthorizationInfo();
        $info
            ->addPermission($permission)
            ->addPermission($permission)
            ->addRole($role)
            ->addRole($role);

        $this->assertSame([
            $permission,
            $permission,
        ], $info->getPermissions());
        $this->assertSame([
            $role,
            $role,
        ], $info->getRoles());
    }
}
