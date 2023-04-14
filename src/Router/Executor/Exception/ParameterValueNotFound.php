<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Executor\Exception;

use ExtendsSoftware\ExaPHP\Router\Executor\ExecutorException;
use InvalidArgumentException;

class ParameterValueNotFound extends InvalidArgumentException implements ExecutorException
{
    /**
     * ParameterValueNotFound constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct(
            sprintf(
                'Value for parameter "%s" can not be found in route match parameters or request attributes and ' .
                'has no default value or allows null.',
                $name
            )
        );
    }
}
