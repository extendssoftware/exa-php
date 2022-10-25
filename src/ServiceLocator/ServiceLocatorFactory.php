<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator;

use ExtendsSoftware\ExaPHP\ServiceLocator\Config\Config;
use ExtendsSoftware\ExaPHP\ServiceLocator\Exception\UnknownResolverType;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\ResolverInterface;

class ServiceLocatorFactory implements ServiceLocatorFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function create(array $config): ServiceLocatorInterface
    {
        $config = new Config($config);
        $serviceLocator = new ServiceLocator($config);
        foreach ($config->get(ServiceLocatorInterface::class, []) as $fqcn => $services) {
            if (!is_string($fqcn) || !is_subclass_of($fqcn, ResolverInterface::class)) {
                throw new UnknownResolverType($fqcn);
            }

            $resolver = $fqcn::factory($services);
            $serviceLocator->addResolver($resolver, $fqcn);
        }

        return $serviceLocator;
    }
}
