<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\RateLimiting;

use ExtendsSoftware\ExaPHP\Authorization\Permission\PermissionInterface;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;
use ExtendsSoftware\ExaPHP\RateLimiting\Quota\QuotaInterface;

interface RateLimiterInterface
{
    /**
     * If identity is allowed by policy.
     *
     * @param PermissionInterface $permission
     * @param IdentityInterface   $identity
     *
     * @return QuotaInterface|null
     */
    public function consume(PermissionInterface $permission, IdentityInterface $identity): ?QuotaInterface;
}
