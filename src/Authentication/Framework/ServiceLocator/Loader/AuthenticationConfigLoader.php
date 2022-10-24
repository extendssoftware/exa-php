<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authentication\Framework\ServiceLocator\Loader;

use ExtendsSoftware\ExaPHP\Authentication\AuthenticatorInterface;
use ExtendsSoftware\ExaPHP\Authentication\Framework\Http\Middleware\AuthenticationMiddleware;
use ExtendsSoftware\ExaPHP\Authentication\Framework\ServiceLocator\Factory\AuthenticatorFactory;
use ExtendsSoftware\ExaPHP\ServiceLocator\Config\Loader\LoaderInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\ReflectionResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

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
                ],
            ],
            AuthenticatorInterface::class => [
                'realms' => [],
            ],
        ];
    }
}
