<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application\Console\Exception;

use ExtendsSoftware\ExaPHP\Application\Console\ConsoleException;
use ExtendsSoftware\ExaPHP\Shell\Command\CommandInterface;
use InvalidArgumentException;

class TaskParameterMissing extends InvalidArgumentException implements ConsoleException
{
    /**
     * TaskParameterMissing constructor.
     *
     * @param CommandInterface $command
     */
    public function __construct(CommandInterface $command)
    {
        parent::__construct(sprintf('Task parameter not defined for command "%s".', $command->getName()));
    }
}
