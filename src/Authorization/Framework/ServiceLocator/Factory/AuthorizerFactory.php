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
        string                  $key,
        ServiceLocatorInterface $serviceLocator,
        array                   $extra = null
    ): AuthorizerInterface {
        $config = $serviceLocator->getConfig()->get(AuthorizerInterface::class, []);
        $authenticator = new Authorizer();
        foreach ($config['realms'] ?? [] as $config) {
            $realm = $serviceLocator->getService($config['name'], $config['options'] ?? []);
            if ($realm instanceof RealmInterface) {
                $authenticator->addRealm($realm);
            }
        }

        return $authenticator;
    }
}
