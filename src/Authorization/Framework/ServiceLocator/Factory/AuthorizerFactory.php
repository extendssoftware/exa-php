<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authorization\Framework\ServiceLocator\Factory;

use ExtendsSoftware\ExaPHP\Authorization\Authorizer;
use ExtendsSoftware\ExaPHP\Authorization\AuthorizerInterface;
use ExtendsSoftware\ExaPHP\Authorization\Realm\RealmInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\ServiceFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class AuthorizerFactory implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     * @throws ServiceLocatorException
     */
    public function createService(
        string $class,
        ServiceLocatorInterface $serviceLocator,
        array $extra = null,
    ): AuthorizerInterface {
        $config = $serviceLocator
            ->getContainer()
            ->find(AuthorizerInterface::class, []);
        $authenticator = new Authorizer();
        foreach ($config['realms'] ?? [] as $config) {
            /** @var class-string<RealmInterface> $name */
            $name = $config['name'];

            $realm = $serviceLocator->getService($name, $config['options'] ?? []);
            $authenticator->addRealm($realm);
        }

        return $authenticator;
    }
}
