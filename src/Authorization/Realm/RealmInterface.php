<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authorization\Realm;

use ExtendsSoftware\ExaPHP\Authorization\Permission\PermissionInterface;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;

interface RealmInterface
{
    /**
     * Get authorization information for identity.
     *
     * @param IdentityInterface $identity
     *
     * @return PermissionInterface[]|null
     */
    public function getPermissions(IdentityInterface $identity): ?array;
}
