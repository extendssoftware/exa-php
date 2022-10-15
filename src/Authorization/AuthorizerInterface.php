<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authorization;

use ExtendsSoftware\ExaPHP\Authorization\Permission\PermissionInterface;
use ExtendsSoftware\ExaPHP\Authorization\Policy\PolicyInterface;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;

interface AuthorizerInterface
{
    /**
     * If identity is permitted for permission.
     *
     * @param PermissionInterface    $permission
     * @param IdentityInterface|null $identity
     *
     * @return bool
     */
    public function isPermitted(PermissionInterface $permission, IdentityInterface $identity = null): bool;

    /**
     * If identity is allowed by policy.
     *
     * @param PolicyInterface        $policy
     * @param IdentityInterface|null $identity
     *
     * @return bool
     */
    public function isAllowed(PolicyInterface $policy, IdentityInterface $identity = null): bool;
}
