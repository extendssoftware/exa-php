<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Firewall\Framework\ServiceLocator\Factory;

use ExtendsSoftware\ExaPHP\Firewall\FirewallInterface;
use ExtendsSoftware\ExaPHP\Firewall\Realm\RealmInterface;
use ExtendsSoftware\ExaPHP\Utility\Container\ContainerInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class FirewallFactoryTest extends TestCase
{
    /**
     * Create service.
     *
     * Test that instance of FirewallInterface will be created.
     *
     * @covers \ExtendsSoftware\ExaPHP\Firewall\Framework\ServiceLocator\Factory\FirewallFactory::createService()
     */
    public function testCreateService(): void
    {
        $realm = $this->createMock(RealmInterface::class);

        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->once())
            ->method('find')
            ->with(FirewallInterface::class)
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
        $factory = new FirewallFactory();
        $firewall = $factory->createService(FirewallInterface::class, $serviceLocator);

        $this->assertInstanceOf(FirewallInterface::class, $firewall);
    }
}
