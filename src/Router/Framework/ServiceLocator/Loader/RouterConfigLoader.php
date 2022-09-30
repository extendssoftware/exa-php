<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Framework\ServiceLocator\Loader;

use ExtendsSoftware\ExaPHP\Router\Controller\Executor\Executor;
use ExtendsSoftware\ExaPHP\Router\Controller\Executor\ExecutorInterface;
use ExtendsSoftware\ExaPHP\Router\Framework\Http\Middleware\Controller\ControllerMiddleware;
use ExtendsSoftware\ExaPHP\Router\Framework\Http\Middleware\Router\RouterMiddleware;
use ExtendsSoftware\ExaPHP\Router\Framework\ServiceLocator\Factory\RouterFactory;
use ExtendsSoftware\ExaPHP\Router\Route\Group\GroupRoute;
use ExtendsSoftware\ExaPHP\Router\Route\Host\HostRoute;
use ExtendsSoftware\ExaPHP\Router\Route\Method\MethodRoute;
use ExtendsSoftware\ExaPHP\Router\Route\Path\PathRoute;
use ExtendsSoftware\ExaPHP\Router\Route\Query\QueryRoute;
use ExtendsSoftware\ExaPHP\Router\Route\Scheme\SchemeRoute;
use ExtendsSoftware\ExaPHP\Router\RouterInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Config\Loader\LoaderInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\ReflectionResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\StaticFactory\StaticFactoryResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class RouterConfigLoader implements LoaderInterface
{
    /**
     * @inheritDoc
     */
    public function load(): array
    {
        return [
            ServiceLocatorInterface::class => [
                FactoryResolver::class => [
                    RouterInterface::class => RouterFactory::class,
                ],
                StaticFactoryResolver::class => [
                    GroupRoute::class => GroupRoute::class,
                    HostRoute::class => HostRoute::class,
                    MethodRoute::class => MethodRoute::class,
                    PathRoute::class => PathRoute::class,
                    QueryRoute::class => QueryRoute::class,
                    SchemeRoute::class => SchemeRoute::class,
                ],
                ReflectionResolver::class => [
                    RouterMiddleware::class => RouterMiddleware::class,
                    ControllerMiddleware::class => ControllerMiddleware::class,
                    ExecutorInterface::class => Executor::class,
                ],
            ],
            RouterInterface::class => [
                'routes' => [],
            ],
        ];
    }
}
