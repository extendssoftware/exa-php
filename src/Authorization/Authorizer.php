<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authorization;

use ExtendsSoftware\ExaPHP\Authorization\Permission\PermissionInterface;
use ExtendsSoftware\ExaPHP\Authorization\Policy\PolicyInterface;
use ExtendsSoftware\ExaPHP\Authorization\Realm\RealmInterface;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;
use function is_array;

class Authorizer implements AuthorizerInterface
{
    /**
     * Realms to get authorization information from.
     *
     * @var RealmInterface[]
     */
    private array $realms = [];

    /**
     * Cached permissions per identity.
     *
     * @var array<mixed, PermissionInterface[]>
     */
    private array $cache = [];

    /**
     * @inheritDoc
     */
    public function isPermitted(PermissionInterface $permission, IdentityInterface $identity): bool
    {
        $permissions = $this->getPermissions($identity);
        foreach ($permissions as $inner) {
            if ($inner->implies($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function isAllowed(PolicyInterface $policy, IdentityInterface $identity): bool
    {
        return $policy->isAllowed($this, $identity);
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

    /**
     * Get permissions for identity.
     *
     * @param IdentityInterface $identity
     *
     * @return PermissionInterface[]
     */
    private function getPermissions(IdentityInterface $identity): array
    {
        $identifier = $identity->getIdentifier();
        if (isset($this->cache[$identifier])) {
            return $this->cache[$identifier];
        }

        $this->cache[$identifier] = [];
        foreach ($this->realms as $realm) {
            $permissions = $realm->getPermissions($identity);
            if (is_array($permissions)) {
                $this->cache[$identifier] = $permissions;

                break;
            }
        }

        return $this->cache[$identifier];
    }
}
