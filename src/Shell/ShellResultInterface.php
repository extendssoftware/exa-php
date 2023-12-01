<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell;

use ExtendsSoftware\ExaPHP\Shell\Command\CommandInterface;

interface ShellResultInterface
{
    /**
     * Get matched command.
     *
     * @return CommandInterface
     */
    public function getCommand(): CommandInterface;

    /**
     * Get parsed data.
     *
     * @return mixed[]
     */
    public function getData(): array;
}
