<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authentication;

use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;

class AuthenticationInfo implements AuthenticationInfoInterface
{
    /**
     * AuthenticationInfo constructor.
     *
     * @param IdentityInterface $identity
     */
    public function __construct(private readonly IdentityInterface $identity)
    {
    }

    /**
     * @inheritDoc
     */
    public function getIdentity(): IdentityInterface
    {
        return $this->identity;
    }
}
