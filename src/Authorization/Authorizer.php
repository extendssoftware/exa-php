<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authorization;

use ExtendsSoftware\ExaPHP\Authorization\Permission\PermissionInterface;
use ExtendsSoftware\ExaPHP\Authorization\Policy\PolicyInterface;
use ExtendsSoftware\ExaPHP\Authorization\Realm\RealmInterface;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;

class Authorizer implements AuthorizerInterface
{
    /**
     * Realms to get authorization information from.
     *
     * @var RealmInterface[]
     */
    private array $realms = [];

    /**
     * @inheritDoc
     */
    public function isPermitted(IdentityInterface $identity, PermissionInterface $permission): bool
    {
        foreach ($this->realms as $realm) {
            $permissions = $realm->getPermissions($identity);
            foreach ($permissions ?? [] as $inner) {
                if ($inner->implies($permission)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function isAllowed(IdentityInterface $identity, PolicyInterface $policy): bool
    {
        return $policy->isAllowed($identity, $this);
    }

    /**
     * Add realm to authorizer.
     *
     * @param RealmInterface $realm
     *
     * @return Authorizer
     */
    public function addRealm(RealmInterface $realm): Authorizer
    {
        $this->realms[] = $realm;

        return $this;
    }
}
