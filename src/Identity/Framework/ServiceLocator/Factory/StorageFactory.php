<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Identity\Framework\ServiceLocator\Factory;

use ExtendsSoftware\ExaPHP\Identity\Storage\InMemory\InMemoryStorage;
use ExtendsSoftware\ExaPHP\Identity\Storage\StorageInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\ServiceFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class StorageFactory implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createService(
        string                  $key,
        ServiceLocatorInterface $serviceLocator,
        array                   $extra = null
    ): StorageInterface {
        return new InMemoryStorage();
    }
}
