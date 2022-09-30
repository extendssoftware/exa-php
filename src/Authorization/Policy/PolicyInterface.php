<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authorization\Policy;

use ExtendsSoftware\ExaPHP\Authorization\AuthorizerInterface;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;

interface PolicyInterface
{
    /**
     * If identity is allowed by policy.
     *
     * @param IdentityInterface   $identity
     * @param AuthorizerInterface $authorizer
     *
     * @return bool
     */
    public function isAllowed(IdentityInterface $identity, AuthorizerInterface $authorizer): bool;
}
