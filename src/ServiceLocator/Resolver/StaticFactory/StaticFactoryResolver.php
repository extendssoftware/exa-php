<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\StaticFactory;

use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\ResolverInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\StaticFactory\Exception\InvalidStaticFactory;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\StaticFactory\Exception\ServiceCreateFailed;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use Throwable;

use function is_string;
use function is_subclass_of;

class StaticFactoryResolver implements ResolverInterface
{
    /**
     * Classes with static methods.
     *
     * @var string[]
     */
    private array $factories = [];

    /**
     * @inheritDoc
     */
    public static function factory(array $services): ResolverInterface
    {
        $resolver = new StaticFactoryResolver();
        foreach ($services as $key => $service) {
            if (!is_string($key)) {
                $key = $service;
            }

            $resolver->addStaticFactory($key, $service);
        }

        return $resolver;
    }

    /**
     * @inheritDoc
     */
    public function hasService(string $key): bool
    {
        return isset($this->factories[$key]);
    }

    /**
     * @inheritDoc
     */
    public function getService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        $factory = $this->factories[$key];
        if (!is_subclass_of($factory, StaticFactoryInterface::class)) {
            throw new InvalidStaticFactory($factory);
        }

        try {
            return $factory::factory($key, $serviceLocator, $extra);
        } catch (Throwable $exception) {
            throw new ServiceCreateFailed($key, $exception);
        }
    }

    /**
     * Add static factory for key.
     *
     * @param string $key
     * @param string $factory
     *
     * @return StaticFactoryResolver
     */
    public function addStaticFactory(string $key, string $factory): StaticFactoryResolver
    {
        $this->factories[$key] = $factory;

        return $this;
    }
}
