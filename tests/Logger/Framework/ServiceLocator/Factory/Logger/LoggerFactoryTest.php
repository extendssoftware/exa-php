<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Framework\ServiceLocator\Factory\Logger;

use ExtendsSoftware\ExaPHP\Logger\Decorator\DecoratorInterface;
use ExtendsSoftware\ExaPHP\Logger\LoggerInterface;
use ExtendsSoftware\ExaPHP\Logger\Writer\WriterInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Utility\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;

class LoggerFactoryTest extends TestCase
{
    /**
     * Create service.
     *
     * Test that factory will return an instance of LoggerInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Logger\Framework\ServiceLocator\Factory\Logger\LoggerFactory::createService()
     */
    public function testCreateService(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->once())
            ->method('find')
            ->with(LoggerInterface::class)
            ->willReturn(
                [
                    'writers' => [
                        [
                            'name' => WriterInterface::class,
                            'options' => [
                                'foo' => 'bar',
                            ],
                        ],
                    ],
                    'decorators' => [
                        [
                            'name' => DecoratorInterface::class,
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
            ->willReturnCallback(fn($service) => match ([$service]) {
                [WriterInterface::class] => $this->createMock(WriterInterface::class),
                [DecoratorInterface::class] => $this->createMock(DecoratorInterface::class),
            });

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $factory = new LoggerFactory();
        $logger = $factory->createService(LoggerInterface::class, $serviceLocator);

        $this->assertInstanceOf(LoggerInterface::class, $logger);
    }
}
