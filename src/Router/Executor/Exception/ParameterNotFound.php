<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Executor\Exception;

use ExtendsSoftware\ExaPHP\Router\Executor\ExecutorException;
use InvalidArgumentException;

class ParameterNotFound extends InvalidArgumentException implements ExecutorException
{
    /**
     * ParameterNotFound constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct(
            sprintf(
                'Parameter name "%s" can not be found in route match parameters and has no default value or ' .
                'allows null.',
                $name
            )
        );
    }
}
