<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use RuntimeException;

class FactoryFailed implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createService(string $class, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        throw new class extends RuntimeException implements ServiceFactoryException {
        };
    }
}
