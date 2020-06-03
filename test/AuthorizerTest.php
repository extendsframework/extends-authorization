<?php
declare(strict_types=1);

namespace ExtendsFramework\Authorization;

use ExtendsFramework\Authorization\Permission\PermissionInterface;
use ExtendsFramework\Authorization\Policy\PolicyInterface;
use ExtendsFramework\Authorization\Realm\RealmInterface;
use ExtendsFramework\Authorization\Role\RoleInterface;
use ExtendsFramework\Identity\IdentityInterface;
use PHPUnit\Framework\TestCase;

class AuthorizerTest extends TestCase
{
    /**
     * Is permitted.
     *
     * Test that permission is permitted for identity.
     *
     * @covers \ExtendsFramework\Authorization\Authorizer::addRealm()
     * @covers \ExtendsFramework\Authorization\Authorizer::getAuthorizationInfo()
     * @covers \ExtendsFramework\Authorization\Authorizer::isPermitted()
     */
    public function testIsPermitted(): void
    {
        $permission = $this->createMock(PermissionInterface::class);
        $permission
            ->expects($this->exactly(2))
            ->method('implies')
            ->with($permission)
            ->willReturnOnConsecutiveCalls(true, false);

        $info = $this->createMock(AuthorizationInfoInterface::class);
        $info
            ->expects($this->exactly(2))
            ->method('getPermissions')
            ->willReturn([
                $permission,
            ]);

        $identity = $this->createMock(IdentityInterface::class);

        $realm = $this->createMock(RealmInterface::class);
        $realm
            ->expects($this->exactly(2))
            ->method('getAuthorizationInfo')
            ->with($identity)
            ->willReturn($info);

        /**
         * @var RealmInterface      $realm
         * @var IdentityInterface   $identity
         * @var PermissionInterface $permission
         */
        $authorizer = new Authorizer();
        $permitted = $authorizer
            ->addRealm($realm)
            ->isPermitted($identity, $permission);

        $this->assertTrue($permitted);

        $permitted = $authorizer
            ->addRealm($realm)
            ->isPermitted($identity, $permission);

        $this->assertFalse($permitted);
    }

    /**
     * Has role.
     *
     * Test that identity has role.
     *
     * @covers \ExtendsFramework\Authorization\Authorizer::addRealm()
     * @covers \ExtendsFramework\Authorization\Authorizer::getAuthorizationInfo()
     * @covers \ExtendsFramework\Authorization\Authorizer::hasRole()
     */
    public function testHasRole(): void
    {
        $role = $this->createMock(RoleInterface::class);
        $role
            ->expects($this->once())
            ->method('isEqual')
            ->with($role)
            ->willReturn(true);

        $info = $this->createMock(AuthorizationInfoInterface::class);
        $info
            ->expects($this->once())
            ->method('getRoles')
            ->willReturn([
                $role,
            ]);

        $identity = $this->createMock(IdentityInterface::class);

        $realm = $this->createMock(RealmInterface::class);
        $realm
            ->expects($this->once())
            ->method('getAuthorizationInfo')
            ->with($identity)
            ->willReturn($info);

        /**
         * @var RealmInterface    $realm
         * @var IdentityInterface $identity
         * @var RoleInterface     $role
         */
        $authorizer = new Authorizer();
        $hasRole = $authorizer
            ->addRealm($realm)
            ->hasRole($identity, $role);

        $this->assertTrue($hasRole);
    }

    /**
     * Is allowed.
     *
     * Test that identity is allowed by policy.
     *
     * @covers \ExtendsFramework\Authorization\Authorizer::isAllowed()
     */
    public function testIsAllowed(): void
    {
        $identity = $this->createMock(IdentityInterface::class);

        $policy = $this->createMock(PolicyInterface::class);
        $policy
            ->expects($this->once())
            ->method('isAllowed')
            ->with($identity, $this->isInstanceOf(AuthorizerInterface::class))
            ->willReturn(true);

        /**
         * @var IdentityInterface $identity
         * @var PolicyInterface   $policy
         */
        $authorizer = new Authorizer();
        $isAllowed = $authorizer->isAllowed($identity, $policy);

        $this->assertTrue($isAllowed);
    }

    /**
     * No authorization info.
     *
     * Test that an empty authorization info instance will be used when none available.
     *
     * @covers \ExtendsFramework\Authorization\Authorizer::addRealm()
     * @covers \ExtendsFramework\Authorization\Authorizer::getAuthorizationInfo()
     * @covers \ExtendsFramework\Authorization\Authorizer::hasRole()
     */
    public function testNoAuthorizationInfo(): void
    {
        $role = $this->createMock(RoleInterface::class);

        $identity = $this->createMock(IdentityInterface::class);

        /**
         * @var RealmInterface    $realm
         * @var IdentityInterface $identity
         * @var RoleInterface     $role
         */
        $authorizer = new Authorizer();

        $this->assertFalse($authorizer->hasRole($identity, $role));
    }
}
