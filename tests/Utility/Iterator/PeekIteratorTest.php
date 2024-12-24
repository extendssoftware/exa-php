<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Utility\Iterator;

use PHPUnit\Framework\TestCase;

class PeekIteratorTest extends TestCase
{
    /**
     * Iterate.
     *
     * Test that iterator can be iterated and keys will be reset for internal pointer.
     *
     * @covers \ExtendsSoftware\ExaPHP\Utility\Iterator\PeekIterator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Iterator\PeekIterator::current()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Iterator\PeekIterator::next()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Iterator\PeekIterator::key()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Iterator\PeekIterator::valid()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Iterator\PeekIterator::rewind()
     */
    public function testIterate(): void
    {
        $iterator = new PeekIterator(array_combine(range('a', 'e'), range(1, 5)));
        $data = iterator_to_array($iterator);

        $this->assertSame($data, array_combine(range(0, 4), range(1, 5)));
    }

    /**
     * Peek.
     *
     * Test that peek method will look given positions ahead.
     *
     * @covers \ExtendsSoftware\ExaPHP\Utility\Iterator\PeekIterator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Iterator\PeekIterator::rewind()
     * @covers \ExtendsSoftware\ExaPHP\Utility\Iterator\PeekIterator::peek()
     */
    public function testPeek2(): void
    {
        $iterator = new PeekIterator(range(1, 5));
        $iterator->rewind();

        $this->assertSame(2, $iterator->peek(1));
        $this->assertSame(3, $iterator->peek(2));
        $this->assertSame(4, $iterator->peek(3));
        $this->assertSame(5, $iterator->peek(4));
        $this->assertSame($this, $iterator->peek(5, $this));

        $this->assertSame(2, $iterator->peek(-1));
        $this->assertSame($this, $iterator->peek(-5, $this));
    }
}
