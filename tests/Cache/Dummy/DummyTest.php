<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Cache\Dummy;

use PHPUnit\Framework\TestCase;

class DummyTest extends TestCase
{
    /**
     * Methods.
     *
     * Test that methods interact with no internal storage.
     *
     * @covers \ExtendsSoftware\ExaPHP\Cache\Dummy\DummyCache::get()
     * @covers \ExtendsSoftware\ExaPHP\Cache\Dummy\DummyCache::set()
     * @covers \ExtendsSoftware\ExaPHP\Cache\Dummy\DummyCache::has()
     * @covers \ExtendsSoftware\ExaPHP\Cache\Dummy\DummyCache::delete()
     * @covers \ExtendsSoftware\ExaPHP\Cache\Dummy\DummyCache::clean()
     */
    public function testMethods(): void
    {
        $cache = new DummyCache();

        $this->assertSame($cache, $cache->set('foo', 'bar'));
        $this->assertSame($this, $cache->get('foo', $this));
        $this->assertFalse($cache->has('foo'));
        $this->assertSame($cache, $cache->delete('foo'));
        $this->assertSame($cache, $cache->clean());
    }
}
