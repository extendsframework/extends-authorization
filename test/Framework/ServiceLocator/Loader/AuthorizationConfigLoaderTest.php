<?php
declare(strict_types=1);

namespace ExtendsFramework\Authorization\Framework\ServiceLocator\Loader;

use ExtendsFramework\Authorization\AuthorizerInterface;
use ExtendsFramework\Authorization\Framework\Http\Middleware\NotAuthorizedMiddleware;
use ExtendsFramework\Authorization\Framework\ServiceLocator\Factory\AuthorizerFactory;
use ExtendsFramework\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsFramework\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsFramework\ServiceLocator\Resolver\Reflection\ReflectionResolver;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class AuthorizationConfigLoaderTest extends TestCase
{
    /**
     * Load.
     *
     * Test that loader returns correct array.
     *
     * @covers \ExtendsFramework\Authorization\Framework\ServiceLocator\Loader\AuthorizationConfigLoader::load()
     */
    public function testLoad()
    {
        $loader = new AuthorizationConfigLoader();

        $this->assertSame([
            ServiceLocatorInterface::class => [
                FactoryResolver::class => [
                    AuthorizerInterface::class => AuthorizerFactory::class,
                ],
                ReflectionResolver::class => [
                    NotAuthorizedMiddleware::class => NotAuthorizedMiddleware::class,
                ],
            ],
            MiddlewareChainInterface::class => [
                NotAuthorizedMiddleware::class => 140,
            ],
            AuthorizerInterface::class => [
                'realms' => [],
            ],
        ], $loader->load());
    }
}
