<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell\Command;

use ExtendsSoftware\ExaPHP\Shell\Definition\DefinitionInterface;
use PHPUnit\Framework\TestCase;

class CommandTest extends TestCase
{
    /**
     * Get parameters.
     *
     * Test if all the get parameters return the given construct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Shell\Command\Command::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Shell\Command\Command::getName()
     * @covers \ExtendsSoftware\ExaPHP\Shell\Command\Command::getDescription()
     * @covers \ExtendsSoftware\ExaPHP\Shell\Command\Command::getDefinition()
     * @covers \ExtendsSoftware\ExaPHP\Shell\Command\Command::getParameters()
     */
    public function testGetParameters(): void
    {
        $definition = $this->createMock(DefinitionInterface::class);

        /**
         * @var DefinitionInterface $definition
         */
        $command = new Command('do.task', 'Some fancy task!', $definition, ['foo' => 'bar']);

        $this->assertSame('do.task', $command->getName());
        $this->assertSame('Some fancy task!', $command->getDescription());
        $this->assertSame($definition, $command->getDefinition());
        $this->assertSame(['foo' => 'bar'], $command->getParameters());
    }
}
