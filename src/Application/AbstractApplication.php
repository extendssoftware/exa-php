<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application;

use ExtendsSoftware\ExaPHP\Application\Module\ModuleInterface;
use ExtendsSoftware\ExaPHP\Application\Module\Provider\ShutdownProviderInterface;
use ExtendsSoftware\ExaPHP\Application\Module\Provider\StartupProviderInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

abstract class AbstractApplication implements ApplicationInterface
{
    /**
     * AbstractApplication constructor.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param ModuleInterface[]       $modules
     */
    public function __construct(
        private readonly ServiceLocatorInterface $serviceLocator,
        private readonly array $modules
    ) {
    }

    /**
     * @inheritDoc
     */
    public function bootstrap(): void
    {
        $serviceLocator = $this->getServiceLocator();

        foreach ($this->modules as $module) {
            if ($module instanceof StartupProviderInterface) {
                $module->onStartup($serviceLocator);
            }
        }

        $this->run();

        foreach ($this->modules as $module) {
            if ($module instanceof ShutdownProviderInterface) {
                $module->onShutdown($serviceLocator);
            }
        }
    }

    /**
     * Get service locator.
     *
     * @return ServiceLocatorInterface
     */
    protected function getServiceLocator(): ServiceLocatorInterface
    {
        return $this->serviceLocator;
    }

    /**
     * Run application.
     *
     * @return AbstractApplication
     */
    abstract protected function run(): AbstractApplication;
}
