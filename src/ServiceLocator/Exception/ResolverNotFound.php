<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Exception;

use Exception;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;

use function sprintf;

class ResolverNotFound extends Exception implements ServiceLocatorException
{
    /**
     * Resolver with key not found.
     *
     * @param string $key
     */
    public function __construct(string $key)
    {
        parent::__construct(
            sprintf(
                'No resolver found for key "%s".',
                $key
            )
        );
    }
}
