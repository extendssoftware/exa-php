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
        $config = $serviceLocator->getConfig()->get(RateLimiterInterface::class, []);
        $rateLimiter = new RateLimiter();
        foreach ($config['realms'] ?? [] as $realmConfig) {
            $realm = $serviceLocator->getService($realmConfig['name'], $realmConfig['options'] ?? []);
            if ($realm instanceof RealmInterface) {
                $rateLimiter->addRealm($realm);
            }
        }

        foreach ($config['algorithms'] ?? [] as $algorithmConfig) {
            $algorithm = $serviceLocator->getService($algorithmConfig['name'], $algorithmConfig['options'] ?? []);
            if ($algorithm instanceof AlgorithmInterface) {
                $rateLimiter->addAlgorithm($algorithm);
            }
        }

        return $rateLimiter;
    }
}
