<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Identity\Framework\ServiceLocator\Loader;

use ExtendsSoftware\ExaPHP\Identity\Framework\ServiceLocator\Factory\StorageFactory;
use ExtendsSoftware\ExaPHP\Identity\Storage\StorageInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Config\Loader\LoaderInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class IdentityConfigLoader implements LoaderInterface
{
    /**
     * @inheritDoc
     */
    public function load(): array
    {
        return [
            ServiceLocatorInterface::class => [
                FactoryResolver::class => [
                    StorageInterface::class => StorageFactory::class,
                ],
            ],
        ];
    }
}
