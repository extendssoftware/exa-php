<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator;

use ExtendsSoftware\ExaPHP\ServiceLocator\Exception\ServiceNotFound;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\ResolverInterface;
use ExtendsSoftware\ExaPHP\Utility\Container\ContainerInterface;

class ServiceLocator implements ServiceLocatorInterface
{
    /**
     * An associative array with all the registered resolvers.
     *
     * @var ResolverInterface[]
     */
    private array $resolvers = [];

    /**
     * An associative array with all the shared services.
     *
     * @var mixed[]
     */
    private array $shared = [];

    /**
     * ServiceLocator constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(private readonly ContainerInterface $container)
    {
        $this->shared[ServiceLocatorInterface::class] = $this;
    }

    /**
     * @inheritDoc
     */
    public function getService(string $key, array $extra = null): object
    {
        if (isset($this->shared[$key]) && $extra === null) {
            return $this->shared[$key];
        }

        $service = null;
        foreach ($this->resolvers as $resolver) {
            if ($resolver->hasService($key)) {
                $service = $resolver->getService($key, $this, $extra);

                if ($extra === null) {
                    $this->shared[$key] = $service;
                }

                break;
            }
        }

        if (!$service) {
            throw new ServiceNotFound($key);
        }

        return $service;
    }

    /**
     * @inheritDoc
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @inheritDoc
     */
    public function isConsole(): bool
    {
        return defined('STDIN');
    }

    /**
     * Register a new resolver for key.
     *
     * When a resolver is already registered for key, it will be overwritten.
     *
     * @param ResolverInterface $resolver
     * @param string            $key
     *
     * @return ServiceLocator
     */
    public function addResolver(ResolverInterface $resolver, string $key): ServiceLocator
    {
        $this->resolvers[$key] = $resolver;

        return $this;
    }
}
