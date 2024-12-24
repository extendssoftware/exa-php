<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application\Console\Exception;

use ExtendsSoftware\ExaPHP\Application\Console\ConsoleException;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;
use ExtendsSoftware\ExaPHP\Shell\Command\CommandInterface;
use InvalidArgumentException;

use function sprintf;

class TaskNotFound extends InvalidArgumentException implements ConsoleException
{
    /**
     * TaskNotFound constructor.
     *
     * @param CommandInterface        $command
     * @param ServiceLocatorException $exception
     */
    public function __construct(CommandInterface $command, ServiceLocatorException $exception)
    {
        parent::__construct(
            sprintf(
                'Task for command "%s" can not be found, see previous exception for more details.',
                $command->getName()
            ),
            previous: $exception
        );
    }
}
