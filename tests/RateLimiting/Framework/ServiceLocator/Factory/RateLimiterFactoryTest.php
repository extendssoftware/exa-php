<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\RateLimiting\Framework\ServiceLocator\Factory;

use ExtendsSoftware\ExaPHP\RateLimiting\Algorithm\AlgorithmInterface;
use ExtendsSoftware\ExaPHP\RateLimiting\RateLimiterInterface;
use ExtendsSoftware\ExaPHP\RateLimiting\Realm\RealmInterface;
use ExtendsSoftware\ExaPHP\Utility\Container\ContainerInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class RateLimiterFactoryTest extends TestCase
{
    /**
     * Create service.
     *
     * Test that instance of RateLimiterInterface will be created.
     *
     * @covers \ExtendsSoftware\ExaPHP\RateLimiting\Framework\ServiceLocator\Factory\RateLimiterFactory::createService()
     */
    public function testCreateService(): void
    {
        $realm = $this->createMock(RealmInterface::class);

        $algorithm = $this->createMock(AlgorithmInterface::class);

        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->once())
            ->method('get')
            ->with(RateLimiterInterface::class)
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
                    'algorithm' => [
                        'name' => AlgorithmInterface::class,
                        'options' => [
                            'qux' => 'quux',
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
            ->expects($this->exactly(2))
            ->method('getService')
            ->willReturnCallback(fn($key, $extra) => match ([$key, $extra]) {
                [AlgorithmInterface::class, ['qux' => 'quux']] => $algorithm,
                [RealmInterface::class, ['foo' => 'bar']] => $realm,
            });

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $factory = new RateLimiterFactory();
        $rateLimiter = $factory->createService(RateLimiterInterface::class, $serviceLocator);

        $this->assertInstanceOf(RateLimiterInterface::class, $rateLimiter);
    }
}
