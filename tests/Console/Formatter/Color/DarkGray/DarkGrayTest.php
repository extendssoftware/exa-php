<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Color\DarkGray;

use PHPUnit\Framework\TestCase;

class DarkGrayTest extends TestCase
{
    /**
     * Get parameters.
     *
     * Test if all the get parameters return the given construct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Formatter\Color\DarkGray\DarkGray::getName()
     */
    public function testGetParameters(): void
    {
        $format = new DarkGray();
        $name = $format->getName();

        $this->assertSame('DarkGray', $name);
    }
}
