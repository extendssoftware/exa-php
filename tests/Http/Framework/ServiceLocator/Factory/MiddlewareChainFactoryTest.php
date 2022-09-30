<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Http\Framework\ServiceLocator\Factory;

use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\MiddlewareInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class MiddlewareChainFactoryTest extends TestCase
{
    /**
     * Create service.
     *
     * Test that factory will return an instance of RouterMiddleware.
     *
     * @covers \ExtendsSoftware\ExaPHP\Http\Framework\ServiceLocator\Factory\MiddlewareChainFactory::createService()
     */
    public function testCreateService(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('getConfig')
            ->willReturn([
                MiddlewareChainInterface::class => [
                    MiddlewareInterface::class => 20,
                ],
            ]);

        $serviceLocator
            ->expects($this->once())
            ->method('getService')
            ->with(MiddlewareInterface::class)
            ->willReturn($this->createMock(MiddlewareInterface::class));

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $factory = new MiddlewareChainFactory();
        $router = $factory->createService(MiddlewareChainInterface::class, $serviceLocator, []);

        $this->assertInstanceOf(MiddlewareChainInterface::class, $router);
    }
}
