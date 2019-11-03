<?php
declare(strict_types=1);

namespace ExtendsFramework\Authorization\Framework\ServiceLocator\Factory;

use ExtendsFramework\Authorization\AuthorizerInterface;
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
                                'foo' => 'bar',
                            ],
                        ],
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
