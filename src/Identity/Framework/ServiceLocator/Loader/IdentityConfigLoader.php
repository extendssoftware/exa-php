<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Identity\Framework\ServiceLocator\Loader;

use ExtendsSoftware\ExaPHP\Identity\Storage\InMemory\InMemoryStorage;
use ExtendsSoftware\ExaPHP\Identity\Storage\StorageInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Config\Loader\LoaderInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Alias\AliasResolver;
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
                AliasResolver::class => [
                    StorageInterface::class => InMemoryStorage::class,
                ],
            ],
        ];
    }
}
