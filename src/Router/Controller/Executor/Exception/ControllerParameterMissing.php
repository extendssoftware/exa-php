<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Controller\Executor\Exception;

use Exception;
use ExtendsSoftware\ExaPHP\Router\Controller\Executor\ExecutorException;

class ControllerParameterMissing extends Exception implements ExecutorException
{
    /**
     * When controller parameter is missing.
     */
    public function __construct()
    {
        parent::__construct('Controller parameter is missing in route match.');
    }
}
