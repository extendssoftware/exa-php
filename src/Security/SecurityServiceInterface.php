<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Security;

use ExtendsSoftware\ExaPHP\Authentication\Header\HeaderInterface;
use ExtendsSoftware\ExaPHP\Authorization\Policy\PolicyInterface;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;

interface SecurityServiceInterface
{
    /**
     * Authenticate header.
     *
     * When authentication fails, false will be returned.
     *
     * @param HeaderInterface $header
     *
     * @return bool
     */
    public function authenticate(HeaderInterface $header): bool;

    /**
     * Get identity.
     *
     * @return IdentityInterface|null
     */
    public function getIdentity(): ?IdentityInterface;

    /**
     * If identity is permitted for permission.
     *
     * @param string $permission
     *
     * @return bool
     */
    public function isPermitted(string $permission): bool;

    /**
     * If policy is allowed by policy.
     *
     * @param PolicyInterface $policy
     *
     * @return bool
     */
    public function isAllowed(PolicyInterface $policy): bool;
}
