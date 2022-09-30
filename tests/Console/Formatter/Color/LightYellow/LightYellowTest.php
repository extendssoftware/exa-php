<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Color\LightYellow;

use PHPUnit\Framework\TestCase;

class LightYellowTest extends TestCase
{
    /**
     * Get parameters.
     *
     * Test if all the get parameters return the given construct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Formatter\Color\LightYellow\LightYellow::getName()
     */
    public function testGetParameters(): void
    {
        $format = new LightYellow();
        $name = $format->getName();

        $this->assertSame('LightYellow', $name);
    }
}
