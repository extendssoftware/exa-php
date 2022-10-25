<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Framework\ServiceLocator\Factory\Logger;

use ExtendsSoftware\ExaPHP\Logger\LoggerInterface;
use ExtendsSoftware\ExaPHP\Logger\Writer\WriterInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Config\ConfigInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
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
        $config = $this->createMock(ConfigInterface::class);
        $config
            ->expects($this->once())
            ->method('get')
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
            ->with(WriterInterface::class, ['foo' => 'bar'])
            ->willReturn($this->createMock(WriterInterface::class));

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $factory = new LoggerFactory();
        $logger = $factory->createService(LoggerInterface::class, $serviceLocator);

        $this->assertInstanceOf(LoggerInterface::class, $logger);
    }
}
