<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Utility\Loader\Cache;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class CacheLoaderTest extends TestCase
{
    /**
     * Save and load.
     *
     * Test that same config will be saved and laded.
     *
     * @covers \ExtendsSoftware\ExaPHP\Utility\Loader\Cache\CacheLoader::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Loader\Cache\CacheLoader::save()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Loader\Cache\CacheLoader::load()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Loader\Cache\CacheLoader::getFilename()
     */
    public function testSaveAndLoad(): void
    {
        $root = vfsStream::setup();
        $filename = $root->url() . '/cache';
        $config = [
            'global' => [
                'baz' => 'baz',
            ],
        ];

        $loader = new CacheLoader($filename);
        $loader->save($config);

        $this->assertSame($config, $loader->load());
    }

    /**
     * Load not cached.
     *
     * Test that load will return an empty array when no cache is available.
     *
     * @covers \ExtendsSoftware\ExaPHP\Utility\Loader\Cache\CacheLoader::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Loader\Cache\CacheLoader::load()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Loader\Cache\CacheLoader::getFilename()
     */
    public function testLoadNotCached(): void
    {
        $root = vfsStream::setup();
        $filename = $root->url() . '/cache';

        $loader = new CacheLoader($filename);

        $this->assertSame([], $loader->load());
    }
}
