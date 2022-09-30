<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Security\Framework\ServiceLocator\Loader;

use ExtendsSoftware\ExaPHP\Security\Framework\Http\Middleware\AuthenticationMiddleware;
use ExtendsSoftware\ExaPHP\Security\Framework\Http\Middleware\AuthorizationMiddleware;
use ExtendsSoftware\ExaPHP\Security\SecurityService;
use ExtendsSoftware\ExaPHP\Security\SecurityServiceInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Config\Loader\LoaderInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\ReflectionResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class SecurityConfigLoader implements LoaderInterface
{
    /**
     * @inheritDoc
     */
    public function load(): array
    {
        return [
            ServiceLocatorInterface::class => [
                ReflectionResolver::class => [
                    AuthorizationMiddleware::class => AuthorizationMiddleware::class,
                    AuthenticationMiddleware::class => AuthenticationMiddleware::class,
                    SecurityServiceInterface::class => SecurityService::class,
                ],
            ],
        ];
    }
}
