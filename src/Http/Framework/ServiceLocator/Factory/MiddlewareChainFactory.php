<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Http\Framework\ServiceLocator\Factory;

use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChain;
use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\MiddlewareInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\ServiceFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class MiddlewareChainFactory implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     * @throws ServiceLocatorException
     */
    public function createService(
        string                  $key,
        ServiceLocatorInterface $serviceLocator,
        array                   $extra = null
    ): MiddlewareChainInterface {
        $config = $serviceLocator->getConfig()->get(MiddlewareChainInterface::class, []);
        $chain = new MiddlewareChain();
        foreach ($config as $middlewareKey => $priority) {
            $middleware = $serviceLocator->getService($middlewareKey);
            if ($middleware instanceof MiddlewareInterface) {
                $chain->addMiddleware($middleware, $priority);
            }
        }

        return $chain;
    }
}
