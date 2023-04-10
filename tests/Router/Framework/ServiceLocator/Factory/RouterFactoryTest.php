<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Framework\ServiceLocator\Factory;

use ExtendsSoftware\ExaPHP\Router\Route\Route;
use ExtendsSoftware\ExaPHP\Router\RouterInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Utility\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;

class RouterFactoryTest extends TestCase
{
    /**
     * Create service.
     *
     * Test that factory will return an instance of RouterInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Framework\ServiceLocator\Factory\RouterFactory::createService()
     */
    public function testCreateService(): void
    {
        $service = new class {
            #[Route('/')]
            public function get()
            {
            }
        };

        $container = $this->createMock(ContainerInterface::class);
        $container
            ->expects($this->once())
            ->method('get')
            ->with(RouterInterface::class)
            ->willReturn(
                [
                    $service,
                    $service,
                    $service,
                ]
            );

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('getContainer')
            ->willReturn($container);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $factory = new RouterFactory();
        $router = $factory->createService(RouterInterface::class, $serviceLocator, []);

        $this->assertInstanceOf(RouterInterface::class, $router);
    }
}
