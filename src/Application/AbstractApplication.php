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
     * Application modules.
     *
     * @var ModuleInterface[]
     */
    private array $modules;

    /**
     * Service locator.
     *
     * @var ServiceLocatorInterface
     */
    private ServiceLocatorInterface $serviceLocator;

    /**
     * AbstractApplication constructor.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param ModuleInterface[]       $modules
     */
    public function __construct(ServiceLocatorInterface $serviceLocator, array $modules)
    {
        $this->modules = $modules;
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @inheritDoc
     */
    public function bootstrap(): void
    {
        foreach ($this->modules as $module) {
            if ($module instanceof StartupProviderInterface) {
                $module->onStartup($this->getServiceLocator());
            }
        }

        $this->run();

        foreach ($this->modules as $module) {
            if ($module instanceof ShutdownProviderInterface) {
                $module->onShutdown($this->getServiceLocator());
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
