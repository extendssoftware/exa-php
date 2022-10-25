<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Framework\ServiceLocator\Factory;

use ExtendsSoftware\ExaPHP\Router\Route\Group\GroupRoute;
use ExtendsSoftware\ExaPHP\Router\Route\Method\MethodRoute;
use ExtendsSoftware\ExaPHP\Router\Route\RouteInterface;
use ExtendsSoftware\ExaPHP\Router\Route\Scheme\SchemeRoute;
use ExtendsSoftware\ExaPHP\Router\RouterInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Config\ConfigInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class RouterFactoryTest extends TestCase
{
    /**
     * Create service.
     *
     * Test that factory will return an instance of RouterInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Framework\ServiceLocator\Factory\RouterFactory::createService()
     * @covers \ExtendsSoftware\ExaPHP\Router\Framework\ServiceLocator\Factory\RouterFactory::createRoute()
     * @covers \ExtendsSoftware\ExaPHP\Router\Framework\ServiceLocator\Factory\RouterFactory::createGroup()
     */
    public function testCreateService(): void
    {
        $config = $this->createMock(ConfigInterface::class);
        $config
            ->expects($this->once())
            ->method('get')
            ->with(RouterInterface::class)
            ->willReturn(
                [
                    'routes' => [
                        'scheme' => [
                            'name' => SchemeRoute::class,
                            'options' => [
                                'scheme' => 'https',
                                'parameters' => [
                                    'foo' => 'bar',
                                ],
                            ],
                            'abstract' => false,
                            'children' => [
                                'post' => [
                                    'name' => MethodRoute::class,
                                    'options' => [
                                        'method' => MethodRoute::METHOD_POST,
                                    ],
                                ],
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

        $route1 = $this->createMock(RouteInterface::class);

        $route2 = $this->createMock(RouteInterface::class);

        $group = $this->createMock(GroupRoute::class);
        $group
            ->method('addRoute')
            ->with($route2)
            ->willReturnSelf();

        $serviceLocator
            ->expects($this->exactly(3))
            ->method('getService')
            ->withConsecutive(
                [
                    SchemeRoute::class,
                    [
                        'scheme' => 'https',
                        'parameters' => [
                            'foo' => 'bar',
                        ],
                    ],
                ],
                [
                    GroupRoute::class,
                    [
                        'route' => $route1,
                        'abstract' => null,
                    ],
                ],
                [
                    MethodRoute::class,
                    [
                        'method' => MethodRoute::METHOD_POST,
                    ],
                ]
            )
            ->willReturnOnConsecutiveCalls($route1, $group, $route2);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $factory = new RouterFactory();
        $router = $factory->createService(RouterInterface::class, $serviceLocator, []);

        $this->assertInstanceOf(RouterInterface::class, $router);
    }
}
