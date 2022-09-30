<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Color\Blue;

use PHPUnit\Framework\TestCase;

class BlueTest extends TestCase
{
    /**
     * Get parameters.
     *
     * Test if all the get parameters return the given construct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Formatter\Color\Blue\Blue::getName()
     */
    public function testGetParameters(): void
    {
        $format = new Blue();
        $name = $format->getName();

        $this->assertSame('Blue', $name);
    }
}
