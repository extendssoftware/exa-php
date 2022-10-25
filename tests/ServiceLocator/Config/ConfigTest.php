<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Config;

use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    /**
     * Get.
     *
     * Test that method will return correct value.
     *
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Config\Config::__construct()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Config\Config::get()
     */
    public function testGet(): void
    {
        $config = new Config([
            'foo' => 'bar',
            'qux' => [
                'quux' => 'baz',
            ],
            'quux' => [
                'bar',
                'baz',
            ],
        ]);

        $this->assertSame('bar', $config->get('foo'));
        $this->assertSame('baz', $config->get('qux.quux'));
        $this->assertSame('bar', $config->get('qux.qux', 'bar'));
        $this->assertSame('bar', $config->get('quux.0'));
        $this->assertSame('baz', $config->get('quux.1'));

        $this->assertNull($config->get('bar'));
        $this->assertNull($config->get('foo.bar'));
        $this->assertNull($config->get('quux.2'));
        $this->assertNull($config->get('quux.'));
        $this->assertNull($config->get('.quux'));
        $this->assertNull($config->get(''));
    }
}
