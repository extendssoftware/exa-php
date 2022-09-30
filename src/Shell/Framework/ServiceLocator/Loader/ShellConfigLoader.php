<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell\Framework\ServiceLocator\Loader;

use ExtendsSoftware\ExaPHP\ServiceLocator\Config\Loader\LoaderInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Shell\Framework\ServiceLocator\Factory\ShellFactory;
use ExtendsSoftware\ExaPHP\Shell\ShellInterface;

class ShellConfigLoader implements LoaderInterface
{
    /**
     * @inheritDoc
     */
    public function load(): array
    {
        return [
            ServiceLocatorInterface::class => [
                FactoryResolver::class => [
                    ShellInterface::class => ShellFactory::class,
                ],
            ],
        ];
    }
}
