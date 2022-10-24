<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\RateLimiting\Framework\ServiceLocator\Loader;

use ExtendsSoftware\ExaPHP\RateLimiting\Framework\ServiceLocator\Factory\RateLimiterFactory;
use ExtendsSoftware\ExaPHP\RateLimiting\RateLimiterInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Config\Loader\LoaderInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

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
            ],
            RateLimiterInterface::class => [
                'algorithms' => [],
                'realms' => [],
            ],
        ];
    }
}
