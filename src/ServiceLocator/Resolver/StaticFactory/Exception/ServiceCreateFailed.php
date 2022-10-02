<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\StaticFactory\Exception;

use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\StaticFactory\StaticFactoryResolverException;
use RuntimeException;
use Throwable;

class ServiceCreateFailed extends RuntimeException implements StaticFactoryResolverException
{
    /**
     * ServiceCreateFailed constructor.
     *
     * @param string    $key
     * @param Throwable $exception
     */
    public function __construct(string $key, Throwable $exception)
    {
        parent::__construct(
            sprintf(
                'Failed to create service for key "%s". See previous exception for more details.',
                $key
            ),
            previous: $exception
        );
    }
}
