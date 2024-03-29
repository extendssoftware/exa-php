<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell;

use ExtendsSoftware\ExaPHP\Shell\Command\CommandInterface;

readonly class ShellResult implements ShellResultInterface
{
    /**
     * Create new shell result.
     *
     * @param CommandInterface $command
     * @param mixed[]          $data
     */
    public function __construct(private CommandInterface $command, private array $data)
    {
    }

    /**
     * @inheritDoc
     */
    public function getCommand(): CommandInterface
    {
        return $this->command;
    }

    /**
     * @inheritDoc
     */
    public function getData(): array
    {
        return $this->data;
    }
}
