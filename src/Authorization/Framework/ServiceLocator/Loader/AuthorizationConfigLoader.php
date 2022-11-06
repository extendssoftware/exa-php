<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authorization\Framework\ServiceLocator\Loader;

use ExtendsSoftware\ExaPHP\Authorization\AuthorizerInterface;
use ExtendsSoftware\ExaPHP\Authorization\Framework\Http\Middleware\AuthorizationMiddleware;
use ExtendsSoftware\ExaPHP\Authorization\Framework\ServiceLocator\Factory\AuthorizerFactory;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\ReflectionResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Utility\Loader\LoaderInterface;

class AuthorizationConfigLoader implements LoaderInterface
{
    /**
     * @inheritDoc
     */
    public function load(): array
    {
        return [
            ServiceLocatorInterface::class => [
                FactoryResolver::class => [
                    AuthorizerInterface::class => AuthorizerFactory::class,
                ],
                ReflectionResolver::class => [
                    AuthorizationMiddleware::class => AuthorizationMiddleware::class,
                ],
            ],
            AuthorizerInterface::class => [
                'realms' => [],
            ],
        ];
    }
}
