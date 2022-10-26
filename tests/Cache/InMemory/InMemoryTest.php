<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Cache\InMemory;

use PHPUnit\Framework\TestCase;

class InMemoryTest extends TestCase
{
    /**
     * Methods.
     *
     * Test that methods interact with internal storage.
     *
     * @covers \ExtendsSoftware\ExaPHP\Cache\InMemory\InMemoryCache::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Cache\InMemory\InMemoryCache::get()
     * @covers \ExtendsSoftware\ExaPHP\Cache\InMemory\InMemoryCache::set()
     * @covers \ExtendsSoftware\ExaPHP\Cache\InMemory\InMemoryCache::has()
     * @covers \ExtendsSoftware\ExaPHP\Cache\InMemory\InMemoryCache::delete()
     * @covers \ExtendsSoftware\ExaPHP\Cache\InMemory\InMemoryCache::clean()
     */
    public function testMethods(): void
    {
        $cache = new InMemoryCache([
            'bar' => 'baz',
        ]);

        $this->assertSame($cache, $cache->set('foo', 'bar'));
        $this->assertSame('bar', $cache->get('foo', $this));

        $this->assertTrue($cache->has('foo'));
        $this->assertSame($cache, $cache->delete('foo'));
        $this->assertFalse($cache->has('foo'));

        $this->assertSame($cache, $cache->clean());
        $this->assertFalse($cache->has('bar'));
    }
}
