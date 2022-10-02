<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application\Framework\ServiceLocator\Factory;

use ExtendsSoftware\ExaPHP\Application\ApplicationInterface;
use ExtendsSoftware\ExaPHP\Application\Console\ConsoleApplication;
use ExtendsSoftware\ExaPHP\Application\Http\HttpApplication;
use ExtendsSoftware\ExaPHP\Application\Module\ModuleInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\ServiceFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Shell\ShellInterface;

class ApplicationFactory implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     * @throws ServiceLocatorException
     */
    public function createService(
        string                  $key,
        ServiceLocatorInterface $serviceLocator,
        array                   $extra = null
    ): ApplicationInterface {
        $modules = $extra['modules'] ?? [];

        if ($extra['console'] ?? false) {
            return $this->getConsoleApplication($serviceLocator, $modules);
        }

        return $this->getHttpApplication($serviceLocator, $modules);
    }

    /**
     * Get console application.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param ModuleInterface[]       $modules
     *
     * @return ConsoleApplication
     * @throws ServiceLocatorException
     */
    private function getConsoleApplication(ServiceLocatorInterface $serviceLocator, array $modules): ConsoleApplication
    {
        /** @var ShellInterface $shell */
        $shell = $serviceLocator->getService(ShellInterface::class);

        return new ConsoleApplication($shell, $serviceLocator, $modules);
    }

    /**
     * Get HTTP application.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param ModuleInterface[]       $modules
     *
     * @return HttpApplication
     * @throws ServiceLocatorException
     */
    private function getHttpApplication(ServiceLocatorInterface $serviceLocator, array $modules): HttpApplication
    {
        /** @var MiddlewareChainInterface $middlewareChain */
        $middlewareChain = $serviceLocator->getService(MiddlewareChainInterface::class);

        /** @var RequestInterface $request */
        $request = $serviceLocator->getService(RequestInterface::class);

        return new HttpApplication($middlewareChain, $request, $serviceLocator, $modules);
    }
}
