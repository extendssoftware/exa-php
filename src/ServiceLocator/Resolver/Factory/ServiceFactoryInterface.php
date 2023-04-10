<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

interface ServiceFactoryInterface
{
    /**
     * Create a service for key.
     *
     * @param string                  $class
     * @param ServiceLocatorInterface $serviceLocator
     * @param mixed[]|null            $extra
     *
     * @return object
     * @throws ServiceFactoryException
     */
    public function createService(string $class, ServiceLocatorInterface $serviceLocator, array $extra = null): object;
}
