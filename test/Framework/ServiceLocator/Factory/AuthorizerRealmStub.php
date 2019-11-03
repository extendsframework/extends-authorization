<?php
declare(strict_types=1);

namespace ExtendsFramework\Authorization\Framework\ServiceLocator\Factory;

use ExtendsFramework\Authorization\AuthorizationInfoInterface;
use ExtendsFramework\Authorization\Realm\RealmInterface;
use ExtendsFramework\Identity\IdentityInterface;
use ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class AuthorizerRealmStub implements RealmInterface, StaticFactoryInterface
{
    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        return new static();
    }

    /**
     * @inheritDoc
     */
    public function getAuthorizationInfo(IdentityInterface $identity): ?AuthorizationInfoInterface
    {
        return null;
    }
}
