<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Utility\Container\ContainerInterface;

class ReflectionA
{
    /**
     * ReflectionA constructor.
     *
     * @param ReflectionB             $b
     * @param ServiceLocatorInterface $serviceLocator
     * @param ContainerInterface      $container
     */
    public function __construct(
        ReflectionB             $b,
        ServiceLocatorInterface $serviceLocator,
        ContainerInterface      $container
    ) {
    }
}
