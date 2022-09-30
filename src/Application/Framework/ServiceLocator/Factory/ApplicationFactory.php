<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application\Framework\ServiceLocator\Factory;

use ExtendsSoftware\ExaPHP\Application\Console\ConsoleApplication;
use ExtendsSoftware\ExaPHP\Application\Http\HttpApplication;
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
    public function createService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        if ($extra['console'] ?? false) {
            return $this->getConsoleApplication($serviceLocator, $extra);
        }

        return $this->getHttpApplication($serviceLocator, $extra);
    }

    /**
     * Get console application.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param mixed[]|null            $extra
     *
     * @return ConsoleApplication
     * @throws ServiceLocatorException
     */
    private function getConsoleApplication(
        ServiceLocatorInterface $serviceLocator,
        array $extra = null
    ): ConsoleApplication {
        /** @var ShellInterface $shell */
        $shell = $serviceLocator->getService(ShellInterface::class);

        return new ConsoleApplication($shell, $serviceLocator, $extra['modules'] ?? []);
    }

    /**
     * Get HTTP application.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param mixed[]|null            $extra
     *
     * @return HttpApplication
     * @throws ServiceLocatorException
     */
    private function getHttpApplication(ServiceLocatorInterface $serviceLocator, array $extra = null): HttpApplication
    {
        /** @var MiddlewareChainInterface $middlewareChain */
        $middlewareChain = $serviceLocator->getService(MiddlewareChainInterface::class);

        /** @var RequestInterface $request */
        $request = $serviceLocator->getService(RequestInterface::class);

        return new HttpApplication($middlewareChain, $request, $serviceLocator, $extra['modules'] ?? []);
    }
}
