<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\RateLimiting\Framework\ServiceLocator\Loader;

use ExtendsSoftware\ExaPHP\RateLimiting\Framework\Http\Middleware\RateLimitingMiddleware;
use ExtendsSoftware\ExaPHP\RateLimiting\Framework\ServiceLocator\Factory\RateLimiterFactory;
use ExtendsSoftware\ExaPHP\RateLimiting\RateLimiterInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\ReflectionResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Utility\Loader\LoaderInterface;

class RateLimitingConfigLoader implements LoaderInterface
{
    /**
     * @inheritDoc
     */
    public function load(): array
    {
        return [
            ServiceLocatorInterface::class => [
                FactoryResolver::class => [
                    RateLimiterInterface::class => RateLimiterFactory::class,
                ],
                ReflectionResolver::class => [
                    RateLimitingMiddleware::class => RateLimitingMiddleware::class,
                ],
            ],
            RateLimiterInterface::class => [
                'algorithm' => null,
                'realms' => [],
            ],
        ];
    }
}
