<?php

namespace ExtendsSoftware\ExaPHP\Firewall;

use ExtendsSoftware\ExaPHP\Firewall\Realm\RealmInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;

class Firewall implements FirewallInterface
{
    /**
     * Realms to use for verification.
     *
     * @var RealmInterface[]
     */
    private array $realms = [];

    /**
     * @inheritDoc
     */
    public function isAllowed(RequestInterface $request): bool
    {
        foreach ($this->realms as $realm) {
            if ($realm->canVerify($request)) {
                if (!$realm->isAllowed($request)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Add realm to firewall.
     *
     * @param RealmInterface $realm
     *
     * @return Firewall
     */
    public function addRealm(RealmInterface $realm): Firewall
    {
        $this->realms[] = $realm;

        return $this;
    }
}
