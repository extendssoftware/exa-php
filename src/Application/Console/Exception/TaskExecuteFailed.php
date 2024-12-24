<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application\Console\Exception;

use ExtendsSoftware\ExaPHP\Application\Console\ConsoleException;
use ExtendsSoftware\ExaPHP\Shell\Command\CommandInterface;
use ExtendsSoftware\ExaPHP\Shell\Task\TaskException;
use InvalidArgumentException;

use function sprintf;

class TaskExecuteFailed extends InvalidArgumentException implements ConsoleException
{
    /**
     * TaskExecuteFailed constructor.
     *
     * @param CommandInterface $command
     * @param TaskException    $exception
     */
    public function __construct(CommandInterface $command, TaskException $exception)
    {
        parent::__construct(
            sprintf(
                'Failed to execute task for command "%s", see previous exception for more details.',
                $command->getName()
            ),
            previous: $exception
        );
    }
}
