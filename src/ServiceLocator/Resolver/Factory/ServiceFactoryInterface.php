<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

interface ServiceFactoryInterface
{
    /**
     * Create a service for key.
     *
     * @param string                  $key
     * @param ServiceLocatorInterface $serviceLocator
     * @param mixed[]|null            $extra
     *
     * @return object
     * @throws ServiceFactoryException
     */
    public function createService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object;
}
