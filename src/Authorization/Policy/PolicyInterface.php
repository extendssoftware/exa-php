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
     * @param AuthorizerInterface $authorizer
     * @param IdentityInterface   $identity
     *
     * @return bool
     */
    public function isAllowed(AuthorizerInterface $authorizer, IdentityInterface $identity): bool;
}
