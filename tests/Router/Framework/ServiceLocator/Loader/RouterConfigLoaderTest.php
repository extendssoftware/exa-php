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
use PHPUnit\Framework\TestCase;

class RouterConfigLoaderTest extends TestCase
{
    /**
     * Load.
     *
     * Test that loader returns correct array.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Framework\ServiceLocator\Loader\RouterConfigLoader::load()
     */
    public function testLoad(): void
    {
        $loader = new RouterConfigLoader();

        $this->assertSame([
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
        ], $loader->load());
    }
}
