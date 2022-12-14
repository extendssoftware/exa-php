<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Color\LightGreen;

use PHPUnit\Framework\TestCase;

class LightGreenTest extends TestCase
{
    /**
     * Get parameters.
     *
     * Test if all the get parameters return the given construct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Formatter\Color\LightGreen\LightGreen::getName()
     */
    public function testGetParameters(): void
    {
        $format = new LightGreen();
        $name = $format->getName();

        $this->assertSame('LightGreen', $name);
    }
}
