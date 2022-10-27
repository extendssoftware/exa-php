<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Firewall\Framework\ServiceLocator\Factory;

use ExtendsSoftware\ExaPHP\Firewall\Firewall;
use ExtendsSoftware\ExaPHP\Firewall\FirewallInterface;
use ExtendsSoftware\ExaPHP\Firewall\Realm\RealmInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\ServiceFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class FirewallFactory implements ServiceFactoryInterface
{
    /**
     * @inheritDoc
     * @throws ServiceLocatorException
     */
    public function createService(
        string                  $key,
        ServiceLocatorInterface $serviceLocator,
        array                   $extra = null
    ): FirewallInterface {
        $config = $serviceLocator->getConfig()->get(FirewallInterface::class, []);
        $firewall = new Firewall();
        foreach ($config['realms'] ?? [] as $config) {
            $realm = $serviceLocator->getService($config['name'], $config['options'] ?? []);
            if ($realm instanceof RealmInterface) {
                $firewall->addRealm($realm);
            }
        }

        return $firewall;
    }
}
