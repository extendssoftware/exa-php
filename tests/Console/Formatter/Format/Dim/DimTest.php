<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Format\Dim;

use PHPUnit\Framework\TestCase;

class DimTest extends TestCase
{
    /**
     * Get parameters.
     *
     * Test if all the get parameters return the given construct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Formatter\Format\Dim\Dim::getName()
     */
    public function testGetParameters(): void
    {
        $format = new Dim();
        $name = $format->getName();

        $this->assertSame('Dim', $name);
    }
}
