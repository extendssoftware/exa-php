<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authorization\Framework\ServiceLocator\Factory;

use ExtendsSoftware\ExaPHP\Authorization\AuthorizerInterface;
use ExtendsSoftware\ExaPHP\Authorization\Realm\RealmInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Config\ConfigInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class AuthorizerFactoryTest extends TestCase
{
    /**
     * Create service.
     *
     * Test that instance of AuthorizerInterface will be created.
     *
     * @covers \ExtendsSoftware\ExaPHP\Authorization\Framework\ServiceLocator\Factory\AuthorizerFactory::createService()
     */
    public function testCreateService(): void
    {
        $realm = $this->createMock(RealmInterface::class);

        $config = $this->createMock(ConfigInterface::class);
        $config
            ->expects($this->once())
            ->method('get')
            ->with(AuthorizerInterface::class)
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
            ->method('getConfig')
            ->willReturn($config);

        $serviceLocator
            ->expects($this->once())
            ->method('getService')
            ->with(RealmInterface::class, ['foo' => 'bar'])
            ->willReturn($realm);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $factory = new AuthorizerFactory();
        $authenticator = $factory->createService(AuthorizerInterface::class, $serviceLocator);

        $this->assertInstanceOf(AuthorizerInterface::class, $authenticator);
    }
}
