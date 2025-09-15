<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator;

use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\ResolverInterface;
use ExtendsSoftware\ExaPHP\Utility\Container\ContainerInterface;

interface ServiceLocatorInterface
{
    /**
     * Get a service with the name key.
     *
     * A shared service will be created when extra is null. If not, a managed service will be created. An exception
     * will be thrown when no service is found for a key or service is not an object.
     *
     * @template T of object
     *
     * @param class-string<T>           $key
     * @param array<string, mixed>|null $extra
     *
     * @return T
     * @throws ServiceLocatorException
     */
    public function getService(string $key, array $extra = null): object;

    /**
     * Get resolver for a key.
     *
     * @param string $key
     *
     * @return ResolverInterface
     * @throws ServiceLocatorException
     */
    public function getResolver(string $key): ResolverInterface;

    /**
     * Get global config.
     *
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface;

    /**
     * If the console is the current environment.
     *
     * @return bool
     */
    public function isConsole(): bool;
}
