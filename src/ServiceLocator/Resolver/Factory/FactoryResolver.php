<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory;

use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\Exception\InvalidFactoryType;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\Exception\ServiceCreateFailed;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\ResolverInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use Throwable;

class FactoryResolver implements ResolverInterface
{
    /**
     * An associative array which holds the factories.
     *
     * @var string[]|ServiceFactoryInterface[]
     */
    private array $factories = [];

    /**
     * @inheritDoc
     */
    public static function factory(array $services): ResolverInterface
    {
        $resolver = new FactoryResolver();
        foreach ($services as $key => $factory) {
            $resolver->addFactory($key, $factory);
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
     * When the factory is a string, a new instance will be created and replaces the string.
     *
     * An exception will be thrown when factory is a string and not a subclass of ServiceFactoryInterface.
     *
     * @inheritDoc
     */
    public function getService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        $factory = $this->factories[$key];

        if (is_string($factory)) {
            if (!is_subclass_of($factory, ServiceFactoryInterface::class)) {
                throw new InvalidFactoryType($factory);
            }

            $factory = new $factory();
            $this->factories[$key] = $factory;
        }

        try {
            return $factory->createService($key, $serviceLocator, $extra);
        } catch (Throwable $exception) {
            throw new ServiceCreateFailed($key, $exception);
        }
    }

    /**
     * Register factory for key.
     *
     * @param string $key
     * @param string $factory
     *
     * @return FactoryResolver
     */
    public function addFactory(string $key, string $factory): FactoryResolver
    {
        $this->factories[$key] = $factory;

        return $this;
    }
}
