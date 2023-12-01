<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Framework\ServiceLocator\Loader;

use ExtendsSoftware\ExaPHP\Router\Executor\Executor;
use ExtendsSoftware\ExaPHP\Router\Executor\ExecutorInterface;
use ExtendsSoftware\ExaPHP\Router\Framework\Http\Middleware\Executor\ExecutorMiddleware;
use ExtendsSoftware\ExaPHP\Router\Framework\Http\Middleware\Router\RouterMiddleware;
use ExtendsSoftware\ExaPHP\Router\Framework\ServiceLocator\Factory\RouterFactory;
use ExtendsSoftware\ExaPHP\Router\RouterInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\ReflectionResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Utility\Loader\LoaderInterface;

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
                ReflectionResolver::class => [
                    RouterMiddleware::class => RouterMiddleware::class,
                    ExecutorMiddleware::class => ExecutorMiddleware::class,
                    ExecutorInterface::class => Executor::class,
                ],
            ],
            RouterInterface::class => [],
        ];
    }
}
