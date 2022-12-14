<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Color\Black;

use PHPUnit\Framework\TestCase;

class BlackTest extends TestCase
{
    /**
     * Get parameters.
     *
     * Test if all the get parameters return the given construct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Formatter\Color\Black\Black::getName()
     */
    public function testGetParameters(): void
    {
        $format = new Black();
        $name = $format->getName();

        $this->assertSame('Black', $name);
    }
}
