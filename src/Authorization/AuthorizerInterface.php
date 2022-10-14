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
     * @param IdentityInterface   $identity
     * @param PermissionInterface $permission
     *
     * @return bool
     */
    public function isPermitted(IdentityInterface $identity, PermissionInterface $permission): bool;

    /**
     * If identity is allowed by policy.
     *
     * @param IdentityInterface $identity
     * @param PolicyInterface   $policy
     *
     * @return bool
     */
    public function isAllowed(IdentityInterface $identity, PolicyInterface $policy): bool;
}
