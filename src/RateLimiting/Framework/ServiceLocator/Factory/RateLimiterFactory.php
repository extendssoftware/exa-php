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
        string                  $key,
        ServiceLocatorInterface $serviceLocator,
        array                   $extra = null
    ): RateLimiterInterface {
        $config = $serviceLocator->getContainer()->get(RateLimiterInterface::class, []);

        $algorithmConfig = $config['algorithm'] ?? [];
        if ($algorithmConfig) {
            /** @var AlgorithmInterface $algorithm */
            $algorithm = $serviceLocator->getService($algorithmConfig['name'], $algorithmConfig['options']);
        }

        $rateLimiter = new RateLimiter($algorithm ?? null);
        foreach ($config['realms'] ?? [] as $realmConfig) {
            $realm = $serviceLocator->getService($realmConfig['name'], $realmConfig['options'] ?? []);
            if ($realm instanceof RealmInterface) {
                $rateLimiter->addRealm($realm);
            }
        }

        return $rateLimiter;
    }
}
