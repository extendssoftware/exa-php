<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Utility\Container;

use ArrayIterator;
use ExtendsSoftware\ExaPHP\Utility\Flattener\FlattenerInterface;
use ExtendsSoftware\ExaPHP\Utility\Merger\MergerInterface;
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
     * Set append.
     *
     * Test that set method with append will set correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::set()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::get()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::getSegments()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::convertObjectsToArrays
     */
    public function testSetAppend(): void
    {
        $container = new Container();
        $container
            ->set('foo', 'bar')
            ->set('foo', 'bar', true);

        $this->assertSame([
            'bar',
            'bar',
        ], $container->get('foo'));

        $container->set('foo', 'bar');

        $this->assertSame('bar', $container->get('foo'));
    }

    /**
     * Unset.
     *
     * Test that unset method will unset correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::set()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::unset()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::get()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::getSegments()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::convertObjectsToArrays
     */
    public function testUnset(): void
    {
        $container = new Container([
            'foo' => [
                'bar' => 'baz',
            ],
        ]);

        $this->assertSame($container, $container->unset('foo.bar.baz'));
        $this->assertSame('baz', $container->get('foo.bar'));

        $this->assertSame($container, $container->unset('foo.bar'));
        $this->assertSame($this, $container->get('foo.bar', $this));
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
     * Empty.
     *
     * Test that empty method will return correct boolean.
     *
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::set()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::empty()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::getSegments()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::convertObjectsToArrays
     */
    public function testEmpty(): void
    {
        $container = new Container();

        $this->assertTrue($container->empty());

        $container->set('foo', 'bar');

        $this->assertFalse($container->empty());
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

    /**
     * Merge.
     *
     * Test that one container will be merged into the other.
     *
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::merge()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::extract()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::convertObjectsToArrays
     */
    public function testMerge(): void
    {
        $merger = $this->createMock(MergerInterface::class);
        $merger
            ->expects($this->once())
            ->method('merge')
            ->with(
                [
                    'bar' => 'baz',
                ],
                [
                    'foo' => 'bar',
                ]
            )
            ->willReturn([
                'bar' => 'baz',
                'foo' => 'bar',
            ]);

        $other = $this->createMock(ContainerInterface::class);
        $other
            ->expects($this->once())
            ->method('extract')
            ->willReturn([
                'foo' => 'bar',
            ]);

        /**
         * @var MergerInterface $merger
         */
        $container = new Container([
            'bar' => 'baz',
        ], merger: $merger);

        $this->assertSame($container, $container->merge($other));
        $this->assertSame([
            'bar' => 'baz',
            'foo' => 'bar',
        ], $container->extract());
    }

    /**
     * Flatten.
     *
     * Test that data will be flattened.
     *
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::flatten()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::convertObjectsToArrays
     */
    public function testFlatten(): void
    {
        $flattener = $this->createMock(FlattenerInterface::class);
        $flattener
            ->expects($this->once())
            ->method('flatten')
            ->with([
                'foo' => [
                    'bar' => 'baz',
                ],
            ])
            ->willReturn([
                'foo.bar' => 'baz',
            ]);

        /**
         * @var FlattenerInterface $flattener
         */
        $container = new Container([
            'foo' => [
                'bar' => 'baz',
            ],
        ], flattener: $flattener);

        $this->assertSame([
            'foo.bar' => 'baz',
        ], $container->flatten());
    }

    /**
     * Array access.
     *
     * Test that array access will redirect to interface methods.
     *
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::offsetSet()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::offsetGet()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::offsetUnset()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::offsetExists()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::convertObjectsToArrays
     */
    public function testArrayAccess(): void
    {
        $container = new Container();
        $container['foo.bar.qux'] = 'baz';
        $container['bar.baz'] = 'foo';
        $container['qux.baz'] = 'bar';
        unset($container['qux']);

        $this->assertTrue(isset($container['bar.baz']));
        $this->assertFalse(isset($container['bar.foo']));
        $this->assertSame([
            'qux' => 'baz',
        ], $container['foo.bar']);
        $this->assertNull($container['qux']);
    }

    /**
     * Iterator.
     *
     * Test that iterator contains data.
     *
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::getIterator()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::convertObjectsToArrays
     */
    public function testIteratorAggregate(): void
    {
        $container = new Container([
            'foo' => 'bar',
        ]);

        $iterator = $container->getIterator();

        $this->assertInstanceOf(ArrayIterator::class, $iterator);
        foreach ($iterator as $key => $value) {
            $this->assertSame('foo', $key);
            $this->assertSame('bar', $value);
        }
    }

    /**
     * JSON serializable.
     *
     * Test that method returns data.
     *
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::jsonSerialize()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Container\Container::convertObjectsToArrays
     */
    public function testJsonSerializable(): void
    {
        $container = new Container([
            'foo' => 'bar',
        ]);

        $this->assertSame([
            'foo' => 'bar',
        ], $container->jsonSerialize());
    }

    /**
     * Overloading.
     *
     * Test that overloading will redirect to interface methods.
     *
     * @covers       \ExtendsSoftware\ExaPHP\Utility\Container\Container::__construct()
     * @covers       \ExtendsSoftware\ExaPHP\Utility\Container\Container::__get()
     * @covers       \ExtendsSoftware\ExaPHP\Utility\Container\Container::__set()
     * @covers       \ExtendsSoftware\ExaPHP\Utility\Container\Container::__isset()
     * @covers       \ExtendsSoftware\ExaPHP\Utility\Container\Container::__unset()
     * @covers       \ExtendsSoftware\ExaPHP\Utility\Container\Container::convertObjectsToArrays
     * @noinspection PhpFieldImmediatelyRewrittenInspection
     */
    public function testOverloading(): void
    {
        $container = new Container();
        $container->{'foo.bar.qux'} = 'baz';
        $container->{'bar.baz'} = 'foo';
        $container->{'qux.baz'} = 'bar';
        unset($container->qux);

        $this->assertTrue(isset($container->{'bar.baz'}));
        $this->assertFalse(isset($container->{'bar.foo'}));
        $this->assertSame([
            'qux' => 'baz',
        ], $container->{'foo.bar'});
        $this->assertNull($container->{'qux'});
    }
}
