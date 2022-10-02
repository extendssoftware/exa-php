<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\Exception;

use Exception;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolverException;
use Throwable;

class ServiceCreateFailed extends Exception implements FactoryResolverException
{
    /**
     * When service create for key fails with exception.
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
