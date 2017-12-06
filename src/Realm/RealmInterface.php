<?php
declare(strict_types=1);

namespace ExtendsFramework\Authorization\Realm;

use ExtendsFramework\Authorization\AuthorizationInfoInterface;
use ExtendsFramework\Identity\IdentityInterface;

interface RealmInterface
{
    /**
     * Get authorization information for $identity.
     *
     * @param IdentityInterface $identity
     * @return AuthorizationInfoInterface|null
     */
    public function getAuthorizationInfo(IdentityInterface $identity): ?AuthorizationInfoInterface;
}
