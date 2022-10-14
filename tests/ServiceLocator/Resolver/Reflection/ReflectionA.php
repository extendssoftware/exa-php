<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class ReflectionA
{
    /**
     * ReflectionA constructor.
     *
     * @param ReflectionB             $b
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ReflectionB $b, ServiceLocatorInterface $serviceLocator)
    {
    }
}
