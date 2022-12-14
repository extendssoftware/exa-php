<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Color\Green;

use PHPUnit\Framework\TestCase;

class GreenTest extends TestCase
{
    /**
     * Get parameters.
     *
     * Test if all the get parameters return the given construct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Formatter\Color\Green\Green::getName()
     */
    public function testGetParameters(): void
    {
        $format = new Green();
        $name = $format->getName();

        $this->assertSame('Green', $name);
    }
}
