<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\StaticFactory;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use RuntimeException;

class StaticFactoryFailed implements StaticFactoryInterface
{
    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        throw new class extends RuntimeException implements StaticFactoryResolverException
        {
        };
    }
}
