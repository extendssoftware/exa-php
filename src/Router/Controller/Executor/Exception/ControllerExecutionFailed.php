<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Controller\Executor\Exception;

use Exception;
use ExtendsSoftware\ExaPHP\Router\Controller\ControllerException;
use ExtendsSoftware\ExaPHP\Router\Controller\Executor\ExecutorException;

class ControllerExecutionFailed extends Exception implements ExecutorException
{
    /**
     * When controller execution throws exception.
     *
     * @param ControllerException $exception
     */
    public function __construct(ControllerException $exception)
    {
        parent::__construct(
            'Failed to execute request to controller. See previous exception for more details.',
            previous: $exception
        );
    }
}
