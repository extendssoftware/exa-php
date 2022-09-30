<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\StaticFactory;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

interface StaticFactoryInterface
{
    /**
     * Create a service for key.
     *
     * @param string                  $key
     * @param ServiceLocatorInterface $serviceLocator
     * @param mixed[]|null            $extra
     *
     * @return object
     * @throws StaticFactoryResolverException
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object;
}
