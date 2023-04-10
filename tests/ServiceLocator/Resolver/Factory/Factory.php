<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use stdClass;

class Factory implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createService(string $class, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        $service = new stdClass();
        $service->key = $class;
        $service->serviceLocator = $serviceLocator;
        $service->extra = $extra;

        return $service;
    }
}
