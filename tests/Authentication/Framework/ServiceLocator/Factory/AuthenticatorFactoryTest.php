<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authentication\Framework\ServiceLocator\Factory;

use ExtendsSoftware\ExaPHP\Authentication\AuthenticatorInterface;
use ExtendsSoftware\ExaPHP\Authentication\Realm\RealmInterface;
use ExtendsSoftware\ExaPHP\Utility\Container\ContainerInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class AuthenticatorFactoryTest extends TestCase
{
    /**
     * Create service.
     *
     * Test that instance of AuthorizerInterface will be created.
     *
     * @covers \ExtendsSoftware\ExaPHP\Authentication\Framework\ServiceLocator\Factory\AuthenticatorFactory::createService()
     */
    public function testCreateService(): void
    {
        $realm = $this->createMock(RealmInterface::class);

        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->once())
            ->method('find')
            ->with(AuthenticatorInterface::class)
            ->willReturn(
                [
                    'realms' => [
                        [
                            'name' => RealmInterface::class,
                            'options' => [
                                'foo' => 'bar',
                            ],
                        ],
                    ],
                ]
            );

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('getContainer')
            ->willReturn($container);

        $serviceLocator
            ->expects($this->once())
            ->method('getService')
            ->with(RealmInterface::class, ['foo' => 'bar'])
            ->willReturn($realm);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $factory = new AuthenticatorFactory();
        $authenticator = $factory->createService(AuthenticatorInterface::class, $serviceLocator);

        $this->assertInstanceOf(AuthenticatorInterface::class, $authenticator);
    }
}
