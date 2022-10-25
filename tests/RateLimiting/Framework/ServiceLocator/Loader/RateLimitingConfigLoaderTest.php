<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\RateLimiting\Framework\ServiceLocator\Loader;

use ExtendsSoftware\ExaPHP\RateLimiting\Framework\Http\Middleware\RateLimitingMiddleware;
use ExtendsSoftware\ExaPHP\RateLimiting\Framework\ServiceLocator\Factory\RateLimiterFactory;
use ExtendsSoftware\ExaPHP\RateLimiting\RateLimiterInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\ReflectionResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class RateLimitingConfigLoaderTest extends TestCase
{
    /**
     * Load.
     *
     * Test that loader returns correct array.
     *
     * @covers \ExtendsSoftware\ExaPHP\RateLimiting\Framework\ServiceLocator\Loader\RateLimitingConfigLoader::load()
     */
    public function testLoad(): void
    {
        $loader = new RateLimitingConfigLoader();

        $this->assertSame([
            ServiceLocatorInterface::class => [
                FactoryResolver::class => [
                    RateLimiterInterface::class => RateLimiterFactory::class,
                ],
                ReflectionResolver::class => [
                    RateLimitingMiddleware::class => RateLimitingMiddleware::class,
                ],
            ],
            RateLimiterInterface::class => [
                'algorithms' => [],
                'realms' => [],
            ],
        ], $loader->load());
    }
}
