<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application\Framework\ServiceLocator\Loader;

use ExtendsSoftware\ExaPHP\Application\ApplicationInterface;
use ExtendsSoftware\ExaPHP\Application\Framework\Http\Middleware\InternalServerErrorMiddleware;
use ExtendsSoftware\ExaPHP\Application\Framework\Http\Middleware\NotImplementedMiddleware;
use ExtendsSoftware\ExaPHP\Application\Framework\Http\Middleware\RendererMiddleware;
use ExtendsSoftware\ExaPHP\Application\Framework\ServiceLocator\Factory\ApplicationFactory;
use ExtendsSoftware\ExaPHP\Application\Http\Renderer\Renderer;
use ExtendsSoftware\ExaPHP\Application\Http\Renderer\RendererInterface;
use ExtendsSoftware\ExaPHP\Authentication\Framework\Http\Middleware\AuthenticationMiddleware;
use ExtendsSoftware\ExaPHP\Authentication\Framework\Http\Middleware\IdentityMiddleware;
use ExtendsSoftware\ExaPHP\Authorization\Framework\Http\Middleware\AuthorizationMiddleware;
use ExtendsSoftware\ExaPHP\Firewall\Framework\Http\Middleware\FirewallMiddleware;
use ExtendsSoftware\ExaPHP\Hateoas\Framework\Http\Middleware\Hateoas\HateoasMiddleware;
use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Logger\Framework\Http\Middleware\Logger\LoggerMiddleware;
use ExtendsSoftware\ExaPHP\ProblemDetails\Framework\Http\Middleware\ProblemDetailsMiddleware;
use ExtendsSoftware\ExaPHP\RateLimiting\Framework\Http\Middleware\RateLimitingMiddleware;
use ExtendsSoftware\ExaPHP\Router\Framework\Http\Middleware\Executor\ExecutorMiddleware;
use ExtendsSoftware\ExaPHP\Router\Framework\Http\Middleware\Router\RouterMiddleware;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Invokable\InvokableResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\ReflectionResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class ApplicationConfigLoaderTest extends TestCase
{
    /**
     * Load.
     *
     * Test that correct config is loaded.
     *
     * @covers \ExtendsSoftware\ExaPHP\Application\Framework\ServiceLocator\Loader\ApplicationConfigLoader::load()
     */
    public function testLoad(): void
    {
        $loader = new ApplicationConfigLoader();

        $this->assertSame([
            ServiceLocatorInterface::class => [
                FactoryResolver::class => [
                    ApplicationInterface::class => ApplicationFactory::class,
                ],
                InvokableResolver::class => [
                    NotImplementedMiddleware::class => NotImplementedMiddleware::class,
                    InternalServerErrorMiddleware::class => InternalServerErrorMiddleware::class,
                    RendererInterface::class => Renderer::class,
                ],
                ReflectionResolver::class => [
                    RendererMiddleware::class => RendererMiddleware::class,
                ],
            ],
            MiddlewareChainInterface::class => [
                RendererMiddleware::class => 1300,
                ProblemDetailsMiddleware::class => 1200,
                InternalServerErrorMiddleware::class => 1100,
                LoggerMiddleware::class => 1000,
                FirewallMiddleware::class => 900,
                IdentityMiddleware::class => 800,
                AuthenticationMiddleware::class => 700,
                HateoasMiddleware::class => 600,
                RouterMiddleware::class => 500,
                AuthorizationMiddleware::class => 400,
                RateLimitingMiddleware::class => 300,
                ExecutorMiddleware::class => 200,
                NotImplementedMiddleware::class => 100,
            ],
        ], $loader->load());
    }
}
