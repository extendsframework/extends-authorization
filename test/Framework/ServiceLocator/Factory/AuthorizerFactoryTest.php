<?php
declare(strict_types=1);

namespace ExtendsFramework\Authorization\Framework\ServiceLocator\Factory;

use ExtendsFramework\Authorization\AuthorizationInfoInterface;
use ExtendsFramework\Authorization\AuthorizerInterface;
use ExtendsFramework\Authorization\Realm\RealmInterface;
use ExtendsFramework\Identity\IdentityInterface;
use ExtendsFramework\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsFramework\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class AuthorizerFactoryTest extends TestCase
{
    /**
     * Create service.
     *
     * Test that instance of AuthorizerInterface will be created.
     *
     * @covers \ExtendsFramework\Authorization\Framework\ServiceLocator\Factory\AuthorizerFactory::createService()
     * @covers \ExtendsFramework\Authorization\Framework\ServiceLocator\Factory\AuthorizerFactory::createRealm()
     */
    public function testCreateService(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('getConfig')
            ->willReturn([
                AuthorizerInterface::class => [
                    'realms' => [
                        [
                            'name' => AuthorizerRealmStub::class,
                            'options' => [
                                'foo' => 'bar'
                            ],
                        ]
                    ],
                ],
            ]);

        $serviceLocator
            ->expects($this->once())
            ->method('getService')
            ->with(AuthorizerRealmStub::class, ['foo' => 'bar'])
            ->willReturn(new AuthorizerRealmStub());

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $factory = new AuthorizerFactory();
        $authenticator = $factory->createService(AuthorizerInterface::class, $serviceLocator);

        $this->assertInstanceOf(AuthorizerInterface::class, $authenticator);
    }
}

class AuthorizerRealmStub implements RealmInterface, StaticFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function getAuthorizationInfo(IdentityInterface $identity): ?AuthorizationInfoInterface
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        return new static();
    }
}
