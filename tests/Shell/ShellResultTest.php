<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell;

use ExtendsSoftware\ExaPHP\Shell\Command\CommandInterface;
use PHPUnit\Framework\TestCase;

class ShellResultTest extends TestCase
{
    /**
     * Get parameters.
     *
     * Test if all the get parameters return the given construct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Shell\ShellResult::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Shell\ShellResult::getCommand()
     * @covers \ExtendsSoftware\ExaPHP\Shell\ShellResult::getData()
     */
    public function testGetParameters(): void
    {
        $command = $this->createMock(CommandInterface::class);

        /**
         * @var CommandInterface $command
         */
        $result = new ShellResult($command, ['foo' => 'bar']);

        $this->assertSame($command, $result->getCommand());
        $this->assertSame(['foo' => 'bar'], $result->getData());
    }
}
