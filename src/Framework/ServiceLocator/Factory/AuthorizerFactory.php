<?php
declare(strict_types=1);

namespace ExtendsFramework\Authorization\Framework\ServiceLocator\Factory;

use ExtendsFramework\Authorization\Authorizer;
use ExtendsFramework\Authorization\AuthorizerInterface;
use ExtendsFramework\ServiceLocator\Resolver\Factory\ServiceFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorException;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;

class AuthorizerFactory implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     * @throws ServiceLocatorException
     */
    public function createService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        $config = $serviceLocator->getConfig();
        $config = $config[AuthorizerInterface::class] ?? [];

        $authenticator = new Authorizer();
        foreach ($config['realms'] ?? [] as $config) {
            /** @noinspection PhpParamsInspection */
            $authenticator->addRealm($serviceLocator->getService($config['name'], $config['options'] ?? []));
        }

        return $authenticator;
    }
}
