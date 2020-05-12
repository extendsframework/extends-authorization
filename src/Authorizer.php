<?php
declare(strict_types=1);

namespace ExtendsFramework\Authorization;

use ExtendsFramework\Authorization\Permission\PermissionInterface;
use ExtendsFramework\Authorization\Realm\RealmInterface;
use ExtendsFramework\Authorization\Role\RoleInterface;
use ExtendsFramework\Identity\IdentityInterface;

class Authorizer implements AuthorizerInterface
{
    /**
     * Realms to get authorization information from.
     *
     * @var RealmInterface[]
     */
    private $realms = [];

    /**
     * @inheritDoc
     */
    public function isPermitted(IdentityInterface $identity, PermissionInterface $permission): bool
    {
        $info = $this->getAuthorizationInfo($identity);
        foreach ($info->getPermissions() as $ownPermission) {
            if ($ownPermission->implies($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get authorization information for $identity.
     *
     * When no authorization information can be found, an empty instance will be returned.
     *
     * @param IdentityInterface $identity
     *
     * @return AuthorizationInfoInterface
     */
    private function getAuthorizationInfo(IdentityInterface $identity): AuthorizationInfoInterface
    {
        foreach ($this->realms as $realm) {
            $info = $realm->getAuthorizationInfo($identity);
            if ($info instanceof AuthorizationInfoInterface) {
                return $info;
            }
        }

        return new AuthorizationInfo();
    }

    /**
     * @inheritDoc
     */
    public function hasRole(IdentityInterface $identity, RoleInterface $role): bool
    {
        $info = $this->getAuthorizationInfo($identity);
        foreach ($info->getRoles() as $ownRole) {
            if ($ownRole->isEqual($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add $realm to authorizer.
     *
     * @param RealmInterface $realm
     *
     * @return Authorizer
     */
    public function addRealm(RealmInterface $realm): Authorizer
    {
        $this->realms[] = $realm;

        return $this;
    }
}
