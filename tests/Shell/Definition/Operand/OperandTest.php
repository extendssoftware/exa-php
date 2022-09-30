<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell\Definition\Operand;

use PHPUnit\Framework\TestCase;

class OperandTest extends TestCase
{
    /**
     * Get name.
     *
     * Test if all the get parameters return the given construct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Shell\Definition\Operand\Operand::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Shell\Definition\Operand\Operand::getName()
     */
    public function testGetParameters(): void
    {
        $operand = new Operand('fooBar');

        $this->assertSame('fooBar', $operand->getName());
    }
}
