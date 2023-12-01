<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application\Module\Provider;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

interface ShutdownProviderInterface
{
    /**
     * Module shutdown.
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return void
     */
    public function onShutdown(ServiceLocatorInterface $serviceLocator): void;
}
