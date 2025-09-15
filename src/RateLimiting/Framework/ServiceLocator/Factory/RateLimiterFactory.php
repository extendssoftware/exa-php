<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\RateLimiting\Framework\ServiceLocator\Factory;

use ExtendsSoftware\ExaPHP\RateLimiting\Algorithm\AlgorithmInterface;
use ExtendsSoftware\ExaPHP\RateLimiting\RateLimiter;
use ExtendsSoftware\ExaPHP\RateLimiting\RateLimiterInterface;
use ExtendsSoftware\ExaPHP\RateLimiting\Realm\RealmInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\ServiceFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class RateLimiterFactory implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     * @throws ServiceLocatorException
     */
    public function createService(
        string $class,
        ServiceLocatorInterface $serviceLocator,
        array $extra = null,
    ): RateLimiterInterface {
        $config = $serviceLocator
            ->getContainer()
            ->find(RateLimiterInterface::class, []);

        $algorithmConfig = $config['algorithm'] ?? [];
        if ($algorithmConfig) {
            /** @var class-string<AlgorithmInterface> $name */
            $name = $algorithmConfig['name'];

            $algorithm = $serviceLocator->getService($name, $algorithmConfig['options']);
        }

        $rateLimiter = new RateLimiter($algorithm ?? null);
        foreach ($config['realms'] ?? [] as $realmConfig) {
            /** @var class-string<RealmInterface> $name */
            $name = $realmConfig['name'];

            $realm = $serviceLocator->getService($name, $realmConfig['options'] ?? []);
            $rateLimiter->addRealm($realm);
        }

        return $rateLimiter;
    }
}
