<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\Exception;

use Exception;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\ReflectionResolverException;
use ReflectionParameter;

class InvalidParameter extends Exception implements ReflectionResolverException
{
    /**
     * Unsupported type for parameter.
     *
     * @param ReflectionParameter $parameter
     */
    public function __construct(ReflectionParameter $parameter)
    {
        parent::__construct(
            sprintf(
                'Reflection parameter "%s" must a named type argument and must be a class.',
                $parameter->getName(),
            )
        );
    }
}
