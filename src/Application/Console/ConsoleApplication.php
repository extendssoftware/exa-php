<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application\Console;

use ExtendsSoftware\ExaPHP\Application\AbstractApplication;
use ExtendsSoftware\ExaPHP\Application\Console\Exception\TaskExecuteFailed;
use ExtendsSoftware\ExaPHP\Application\Console\Exception\TaskNotFound;
use ExtendsSoftware\ExaPHP\Application\Console\Exception\TaskParameterMissing;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Shell\ShellInterface;
use ExtendsSoftware\ExaPHP\Shell\ShellResultInterface;
use ExtendsSoftware\ExaPHP\Shell\Task\TaskException;
use ExtendsSoftware\ExaPHP\Shell\Task\TaskInterface;

class ConsoleApplication extends AbstractApplication
{
    /**
     * Shell.
     *
     * @var ShellInterface
     */
    private ShellInterface $shell;

    /**
     * @inheritDoc
     */
    public function __construct(ShellInterface $shell, ServiceLocatorInterface $serviceLocator, array $modules)
    {
        parent::__construct($serviceLocator, $modules);

        $this->shell = $shell;
    }

    /**
     * @inheritDoc
     * @throws ConsoleException
     */
    protected function run(): AbstractApplication
    {
        $result = $this->shell->process(array_slice($GLOBALS['argv'], 1));
        if ($result instanceof ShellResultInterface) {
            $command = $result->getCommand();
            $parameters = $command->getParameters();
            if (!isset($parameters['task'])) {
                throw new TaskParameterMissing($command);
            }

            try {
                /** @var TaskInterface $task */
                $task = $this
                    ->getServiceLocator()
                    ->getService($parameters['task']);
            } catch (ServiceLocatorException $exception) {
                throw new TaskNotFound($command, $exception);
            }

            try {
                $task->execute($result->getData());
            } catch (TaskException $exception) {
                throw new TaskExecuteFailed($command, $exception);
            }
        }

        return $this;
    }
}
