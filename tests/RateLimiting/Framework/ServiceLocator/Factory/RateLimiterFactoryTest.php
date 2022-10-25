<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\RateLimiting\Framework\ServiceLocator\Factory;

use ExtendsSoftware\ExaPHP\RateLimiting\Algorithm\AlgorithmInterface;
use ExtendsSoftware\ExaPHP\RateLimiting\RateLimiterInterface;
use ExtendsSoftware\ExaPHP\RateLimiting\Realm\RealmInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Config\ConfigInterface;
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

        $config = $this->createMock(ConfigInterface::class);
        $config
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
                    'algorithms' => [
                        [
                            'name' => AlgorithmInterface::class,
                            'options' => [
                                'qux' => 'quux',
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
            ->expects($this->exactly(2))
            ->method('getService')
            ->withConsecutive(
                [RealmInterface::class, ['foo' => 'bar']],
                [AlgorithmInterface::class, ['qux' => 'quux']]
            )
            ->willReturnOnConsecutiveCalls(
                $realm,
                $algorithm
            );

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $factory = new RateLimiterFactory();
        $rateLimiter = $factory->createService(RateLimiterInterface::class, $serviceLocator);

        $this->assertInstanceOf(RateLimiterInterface::class, $rateLimiter);
    }
}
