<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Firewall\Framework\ServiceLocator\Loader;

use ExtendsSoftware\ExaPHP\Firewall\FirewallInterface;
use ExtendsSoftware\ExaPHP\Firewall\Framework\Http\Middleware\FirewallMiddleware;
use ExtendsSoftware\ExaPHP\Firewall\Framework\ServiceLocator\Factory\FirewallFactory;
use ExtendsSoftware\ExaPHP\ServiceLocator\Config\Loader\LoaderInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\ReflectionResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class FirewallConfigLoader implements LoaderInterface
{
    /**
     * @inheritDoc
     */
    public function load(): array
    {
        return [
            ServiceLocatorInterface::class => [
                FactoryResolver::class => [
                    FirewallInterface::class => FirewallFactory::class,
                ],
                ReflectionResolver::class => [
                    FirewallMiddleware::class => FirewallMiddleware::class,
                ],
            ],
            FirewallInterface::class => [
                'realms' => [],
            ],
        ];
    }
}
