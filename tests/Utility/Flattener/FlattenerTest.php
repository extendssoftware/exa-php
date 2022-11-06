<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Utility\Flattener;

use PHPUnit\Framework\TestCase;

class FlattenerTest extends TestCase
{
    /**
     * Flatten.
     *
     * Test that flatten method will return correct flattened array.
     *
     * @covers \ExtendsSoftware\ExaPHP\Utility\Flattener\Flattener::flatten()
     */
    public function testMerge(): void
    {
        $flattener = new Flattener();
        $flattened = $flattener->flatten([
            'foo' => [
                'qux' => [
                    'baz' => [
                        'foo' => 'quux',
                        'bar' => 'baz',
                    ],
                ],
            ],
            'bar' => 'baz',
            'quux',
        ], '.');

        $this->assertSame([
            'foo.qux.baz.foo' => 'quux',
            'foo.qux.baz.bar' => 'baz',
            'bar' => 'baz',
            0 => 'quux',
        ], $flattened);
    }
}
