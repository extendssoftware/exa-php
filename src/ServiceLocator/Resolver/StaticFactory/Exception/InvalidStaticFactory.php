<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\StaticFactory\Exception;

use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\StaticFactory\StaticFactoryResolverException;
use RuntimeException;

class InvalidStaticFactory extends RuntimeException implements StaticFactoryResolverException
{
    /**
     * InvalidStaticFactory constructor.
     *
     * @param string $factory
     */
    public function __construct(string $factory)
    {
        parent::__construct(
            sprintf(
                'Factory must be a subclass of StaticFactoryInterface, got "%s".',
                $factory
            )
        );
    }
}
