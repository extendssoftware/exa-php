<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Exception;

use Exception;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;

use function sprintf;

class ServiceNotFound extends Exception implements ServiceLocatorException
{
    /**
     * Service with key not found.
     *
     * @param string $key
     */
    public function __construct(string $key)
    {
        parent::__construct(
            sprintf(
                'No service found for key "%s".',
                $key
            )
        );
    }
}
