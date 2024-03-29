<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Cache\Framework\ServiceLocator\Loader;

use ExtendsSoftware\ExaPHP\Cache\CacheInterface;
use ExtendsSoftware\ExaPHP\Cache\Dummy\DummyCache;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Alias\AliasResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Utility\Loader\LoaderInterface;

class CacheConfigLoader implements LoaderInterface
{
    /**
     * @inheritDoc
     */
    public function load(): array
    {
        return [
            ServiceLocatorInterface::class => [
                AliasResolver::class => [
                    CacheInterface::class => DummyCache::class,
                ],
            ],
        ];
    }
}
