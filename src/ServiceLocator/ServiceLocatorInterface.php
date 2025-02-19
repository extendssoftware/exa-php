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
     * will be thrown when no service is found for key or service is not an object.
     *
     * @param string       $key
     * @param mixed[]|null $extra
     *
     * @return object
     * @throws ServiceLocatorException
     */
    public function getService(string $key, array $extra = null): object;

    /**
     * Get resolver for key.
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
     * If console is the current environment.
     *
     * @return bool
     */
    public function isConsole(): bool;
}
