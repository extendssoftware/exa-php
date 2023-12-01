<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authentication\Framework\ServiceLocator\Factory;

use ExtendsSoftware\ExaPHP\Authentication\Authenticator;
use ExtendsSoftware\ExaPHP\Authentication\AuthenticatorInterface;
use ExtendsSoftware\ExaPHP\Authentication\Realm\RealmInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\ServiceFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class AuthenticatorFactory implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     * @throws ServiceLocatorException
     */
    public function createService(
        string $class,
        ServiceLocatorInterface $serviceLocator,
        array $extra = null
    ): AuthenticatorInterface {
        $config = $serviceLocator->getContainer()->find(AuthenticatorInterface::class, []);
        $authenticator = new Authenticator();
        foreach ($config['realms'] ?? [] as $config) {
            $realm = $serviceLocator->getService($config['name'], $config['options'] ?? []);
            if ($realm instanceof RealmInterface) {
                $authenticator->addRealm($realm);
            }
        }

        return $authenticator;
    }
}
