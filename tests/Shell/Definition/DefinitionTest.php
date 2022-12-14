<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell\Definition;

use ExtendsSoftware\ExaPHP\Shell\Definition\Exception\OperandNotFound;
use ExtendsSoftware\ExaPHP\Shell\Definition\Exception\OptionNotFound;
use ExtendsSoftware\ExaPHP\Shell\Definition\Operand\OperandInterface;
use ExtendsSoftware\ExaPHP\Shell\Definition\Option\OptionInterface;
use PHPUnit\Framework\TestCase;

class DefinitionTest extends TestCase
{
    /**
     * Add and get.
     *
     * Test that options and operands can be added and received.
     *
     * @covers \ExtendsSoftware\ExaPHP\Shell\Definition\Definition::addOption()
     * @covers \ExtendsSoftware\ExaPHP\Shell\Definition\Definition::addOperand()
     * @covers \ExtendsSoftware\ExaPHP\Shell\Definition\Definition::getOption()
     * @covers \ExtendsSoftware\ExaPHP\Shell\Definition\Definition::getOperand()
     * @covers \ExtendsSoftware\ExaPHP\Shell\Definition\Definition::getOptions()
     * @covers \ExtendsSoftware\ExaPHP\Shell\Definition\Definition::getOperands()
     */
    public function testAddAndGet(): void
    {
        $short = $this->createMock(OptionInterface::class);
        $short
            ->expects($this->once())
            ->method('getShort')
            ->willReturn('f');

        $short
            ->expects($this->once())
            ->method('getLong')
            ->willReturn(null);

        $long = $this->createMock(OptionInterface::class);

        $long
            ->expects($this->once())
            ->method('getLong')
            ->willReturn('force');

        $operand = $this->createMock(OperandInterface::class);

        /**
         * @var OptionInterface  $short
         * @var OptionInterface  $long
         * @var OperandInterface $operand
         */
        $definition = new Definition();
        $definition
            ->addOption($short)
            ->addOption($long)
            ->addOperand($operand)
            ->addOperand($operand);

        $this->assertSame($short, $definition->getOption('f'));
        $this->assertSame($long, $definition->getOption('force', true));
        $this->assertSame($operand, $definition->getOperand(1));

        $this->assertContainsOnlyInstancesOf(OptionInterface::class, $definition->getOptions());
        $this->assertContainsOnlyInstancesOf(OperandInterface::class, $definition->getOperands());
    }

    /**
     * Short option not found.
     *
     * Test that short option ('f') can not be found and an exception will be thrown.
     *
     * @covers \ExtendsSoftware\ExaPHP\Shell\Definition\Definition::getOption()
     * @covers \ExtendsSoftware\ExaPHP\Shell\Definition\Exception\OptionNotFound::__construct()
     */
    public function testOptionNotFound(): void
    {
        $this->expectException(OptionNotFound::class);
        $this->expectExceptionMessage('No short option found for name "-f".');

        $definition = new Definition();
        $definition->getOption('f');
    }

    /**
     * Long option not found.
     *
     * Test that long option ('force') can not be found and an exception will be thrown.
     *
     * @covers \ExtendsSoftware\ExaPHP\Shell\Definition\Definition::getOption()
     * @covers \ExtendsSoftware\ExaPHP\Shell\Definition\Exception\OptionNotFound::__construct()
     */
    public function testCanNotGetLongOption(): void
    {
        $this->expectException(OptionNotFound::class);
        $this->expectExceptionMessage('No long option found for name "--force".');

        $definition = new Definition();
        $definition->getOption('force', true);
    }

    /**
     * Operand not found.
     *
     * Test that long option for position (0) can not be found and an exception will be thrown.
     *
     * @covers \ExtendsSoftware\ExaPHP\Shell\Definition\Definition::getOperand()
     * @covers \ExtendsSoftware\ExaPHP\Shell\Definition\Exception\OperandNotFound::__construct()
     */
    public function testCanNotGetOperand(): void
    {
        $this->expectException(OperandNotFound::class);
        $this->expectExceptionMessage('No operand found for position "0".');

        $definition = new Definition();
        $definition->getOperand(0);
    }
}
