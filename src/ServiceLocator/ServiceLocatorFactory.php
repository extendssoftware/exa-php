<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator;

use ExtendsSoftware\ExaPHP\ServiceLocator\Exception\UnknownResolverType;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\ResolverInterface;
use ExtendsSoftware\ExaPHP\Utility\Container\Container;

use function is_string;
use function is_subclass_of;

class ServiceLocatorFactory implements ServiceLocatorFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function create(array $config): ServiceLocatorInterface
    {
        $config = new Container($config);
        $serviceLocator = new ServiceLocator($config);
        foreach ($config->find(ServiceLocatorInterface::class, []) as $fqcn => $services) {
            if (!is_string($fqcn) || !is_subclass_of($fqcn, ResolverInterface::class)) {
                throw new UnknownResolverType($fqcn);
            }

            $resolver = $fqcn::factory($services);
            $serviceLocator->addResolver($resolver, $fqcn);
        }

        return $serviceLocator;
    }
}
