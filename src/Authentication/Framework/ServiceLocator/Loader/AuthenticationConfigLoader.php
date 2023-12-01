<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authentication\Framework\ServiceLocator\Loader;

use ExtendsSoftware\ExaPHP\Authentication\AuthenticatorInterface;
use ExtendsSoftware\ExaPHP\Authentication\Framework\Http\Middleware\AuthenticationMiddleware;
use ExtendsSoftware\ExaPHP\Authentication\Framework\Http\Middleware\IdentityMiddleware;
use ExtendsSoftware\ExaPHP\Authentication\Framework\ServiceLocator\Factory\AuthenticatorFactory;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\ReflectionResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Utility\Loader\LoaderInterface;

class AuthenticationConfigLoader implements LoaderInterface
{
    /**
     * @inheritDoc
     */
    public function load(): array
    {
        return [
            ServiceLocatorInterface::class => [
                FactoryResolver::class => [
                    AuthenticatorInterface::class => AuthenticatorFactory::class,
                ],
                ReflectionResolver::class => [
                    AuthenticationMiddleware::class => AuthenticationMiddleware::class,
                    IdentityMiddleware::class => IdentityMiddleware::class,
                ],
            ],
            AuthenticatorInterface::class => [
                'realms' => [],
            ],
        ];
    }
}
