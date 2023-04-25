<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Utility\Container;

use ExtendsSoftware\ExaPHP\Utility\Container\Exception\PathNotFound;
use PHPUnit\Framework\TestCase;
use stdClass;

class ContainerTest extends TestCase
{
    /**
     * Find.
     *
     * Test that find method return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::find()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::getSegments()
     */
    public function testFind(): void
    {
        $object = new stdClass();
        $object->qux = 'quux';

        $container = new Container([
            'baz' => [
                $object,
            ],
            'foo' => 'bar',
            'bar' => [
                'baz' => 'foo',
                'qux' => [
                    'baz' => 'quux',
                ],
            ],
        ]);

        $this->assertSame('bar', $container->find('foo'));
        $this->assertSame('foo', $container->find('bar.baz'));
        $this->assertSame('quux', $container->find('bar.qux.baz'));
        $this->assertSame('quux', $container->find('baz.0.qux'));
        $this->assertSame('default', $container->find('qux', 'default'));
    }

    /**
     * Get.
     *
     * Test that method will return correct value when path exists and throws exception when path not exists.
     *
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::find()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::get()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::getSegments()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Exception\PathNotFound::__construct()
     */
    public function testGet(): void
    {
        $this->expectException(PathNotFound::class);
        $this->expectExceptionMessage('Path "bar.baz" in container can not be found.');

        $container = new Container([
            'foo' => 'bar',
        ]);

        $this->assertSame('bar', $container->get('foo'));
        $container->get('bar.baz');
    }

    /**
     * Has.
     *
     * Test that has method will return correct boolean.
     *
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::has()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::getSegments()
     */
    public function testHas(): void
    {
        $container = new Container([
            'foo' => 'bar',
        ]);

        $this->assertTrue($container->has('foo'));
        $this->assertFalse($container->has('foo.bar'));
        $this->assertFalse($container->has('qux'));
    }

    /**
     * Has.
     *
     * Test that extract method will return correct data.
     *
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::extract()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::getSegments()
     */
    public function testExtract(): void
    {
        $container = new Container([
            'foo' => [
                'bar' => [
                    'baz' => 'qux',
                    'foo' => (object)[
                        'qux' => 'quux',
                    ],
                ],
            ],
        ]);

        $this->assertEquals([
            'foo' => [
                'bar' => [
                    'baz' => 'qux',
                    'foo' => (object)[
                        'qux' => 'quux',
                    ],
                ],
            ],
        ], $container->extract());
    }

    /**
     * Delimiter.
     *
     * Test that container will work with different delimiter.
     *
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::find()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::getSegments()
     */
    public function testDelimiter(): void
    {
        $container = new Container([
            'foo' => [
                'bar' => [
                    'baz' => 'qux',
                    'foo' => [
                        'qux' => 'quux',
                    ],
                ],
            ],
        ], '|');

        $this->assertSame('quux', $container->find('foo|bar|foo|qux'));
    }
}
