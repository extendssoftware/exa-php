<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Cache\Framework\ServiceLocator\Loader;

use ExtendsSoftware\ExaPHP\Cache\CacheInterface;
use ExtendsSoftware\ExaPHP\Cache\Dummy\DummyCache;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Alias\AliasResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class CacheConfigLoaderTest extends TestCase
{
    /**
     * Load.
     *
     * Test that loader returns correct array.
     *
     * @covers \ExtendsSoftware\ExaPHP\Cache\Framework\ServiceLocator\Loader\CacheConfigLoader::load()
     */
    public function testLoad(): void
    {
        $loader = new CacheConfigLoader();

        $this->assertSame([
            ServiceLocatorInterface::class => [
                AliasResolver::class => [
                    CacheInterface::class => DummyCache::class,
                ],
            ],
        ], $loader->load());
    }
}
