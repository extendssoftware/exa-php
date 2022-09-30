<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Identity\Framework\ServiceLocator\Loader;

use ExtendsSoftware\ExaPHP\Identity\Framework\ServiceLocator\Factory\StorageFactory;
use ExtendsSoftware\ExaPHP\Identity\Storage\StorageInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class IdentityConfigLoaderTest extends TestCase
{
    /**
     * Load.
     *
     * Test that loader returns correct array.
     *
     * @covers \ExtendsSoftware\ExaPHP\Identity\Framework\ServiceLocator\Loader\IdentityConfigLoader::load()
     */
    public function testLoad(): void
    {
        $loader = new IdentityConfigLoader();

        $this->assertSame([
            ServiceLocatorInterface::class => [
                FactoryResolver::class => [
                    StorageInterface::class => StorageFactory::class,
                ],
            ],
        ], $loader->load());
    }
}
