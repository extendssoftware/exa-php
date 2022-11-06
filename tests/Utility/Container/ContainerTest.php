<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Utility\Container;

use PHPUnit\Framework\TestCase;
use stdClass;

class ContainerTest extends TestCase
{
    /**
     * Get.
     *
     * Test that get method return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::get()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::getSegments()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::convertObjectsToArrays
     */
    public function testGet(): void
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

        $this->assertSame('bar', $container->get('foo'));
        $this->assertSame('foo', $container->get('bar.baz'));
        $this->assertSame('quux', $container->get('bar.qux.baz'));
        $this->assertSame('quux', $container->get('baz.0.qux'));
        $this->assertSame('default', $container->get('qux', 'default'));
    }

    /**
     * Set.
     *
     * Test that set method will set correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::set()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::get()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::getSegments()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::convertObjectsToArrays
     */
    public function testSet(): void
    {
        $object = new stdClass();
        $object->qux = 'quux';

        $container = new Container();
        $container
            ->set('foo', 'bar')
            ->set('baz', [
                'qux' => 'quux',
            ])
            ->set('baz.qux', [
                'bar' => 'quux',
            ]);

        $this->assertSame($container, $container->set('bar', $object));
        $this->assertSame('bar', $container->get('foo'));
        $this->assertNotSame('quux', $container->get('baz.qux'));
        $this->assertSame('quux', $container->get('baz.qux.bar'));
        $this->assertSame('quux', $container->get('bar.qux'));
    }

    /**
     * Has.
     *
     * Test that has method will return correct boolean.
     *
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::set()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::has()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::getSegments()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::convertObjectsToArrays
     */
    public function testHas(): void
    {
        $container = new Container();
        $container->set('foo', 'bar');

        $this->assertTrue($container->has('foo'));
        $this->assertFalse($container->has('foo.bar'));
        $this->assertFalse($container->has('qux'));
    }

    /**
     * Clear.
     *
     * Test that clear method will clear whole container.
     *
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::clear()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::convertObjectsToArrays
     */
    public function testClear(): void
    {
        $container = new Container([
            'foo' => [
                'bar' => 'baz',
                'qux' => 'foo',
            ],
        ]);

        $this->assertSame($container, $container->clear());
        $this->assertEmpty($container->extract());
    }

    /**
     * Has.
     *
     * Test that extract method will return correct data.
     *
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::set()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::extract()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::getSegments()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::convertObjectsToArrays
     */
    public function testExtract(): void
    {
        $object = new stdClass();
        $object->qux = 'quux';

        $container = new Container();

        $this->assertSame([], $container->extract());

        $container
            ->set('foo.bar.baz', 'qux')
            ->set('foo.bar.foo', $object);

        $this->assertSame([
            'foo' => [
                'bar' => [
                    'baz' => 'qux',
                    'foo' => [
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
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::set()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::get()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::getSegments()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::convertObjectsToArrays
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
        
        $this->assertSame('quux', $container->get('foo|bar|foo|qux'));
    }
}
