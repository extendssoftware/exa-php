<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application\Module\Provider;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

interface StartupProviderInterface
{
    /**
     * Module startup.
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return void
     */
    public function onStartup(ServiceLocatorInterface $serviceLocator): void;
}
