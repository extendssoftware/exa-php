<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Format\Bold;

use PHPUnit\Framework\TestCase;

class BoldTest extends TestCase
{
    /**
     * Get parameters.
     *
     * Test if all the get parameters return the given construct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Formatter\Format\Bold\Bold::getName()
     */
    public function testGetParameters(): void
    {
        $format = new Bold();
        $name = $format->getName();

        $this->assertSame('Bold', $name);
    }
}
