<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Security;

use ExtendsSoftware\ExaPHP\Authentication\AuthenticatorInterface;
use ExtendsSoftware\ExaPHP\Authentication\Header\HeaderInterface;
use ExtendsSoftware\ExaPHP\Authorization\AuthorizerInterface;
use ExtendsSoftware\ExaPHP\Authorization\Permission\Permission;
use ExtendsSoftware\ExaPHP\Authorization\Policy\PolicyInterface;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;

class SecurityService implements SecurityServiceInterface
{
    /**
     * SecurityService constructor.
     *
     * @param AuthenticatorInterface $authenticator
     * @param AuthorizerInterface    $authorizer
     * @param IdentityInterface|null $identity
     */
    public function __construct(
        private readonly AuthenticatorInterface $authenticator,
        private readonly AuthorizerInterface    $authorizer,
        private ?IdentityInterface              $identity = null
    ) {
    }

    /**
     * @inheritDoc
     */
    public function authenticate(HeaderInterface $header): bool
    {
        $identity = $this->authenticator->authenticate($header);
        if ($identity instanceof IdentityInterface) {
            $this->identity = $identity;

            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function isPermitted(string $permission): bool
    {
        if ($this->identity instanceof IdentityInterface) {
            return $this->authorizer->isPermitted($this->identity, new Permission($permission));
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function getIdentity(): ?IdentityInterface
    {
        return $this->identity;
    }

    /**
     * @inheritDoc
     */
    public function isAllowed(PolicyInterface $policy): bool
    {
        if ($this->identity instanceof IdentityInterface) {
            return $this->authorizer->isAllowed($this->identity, $policy);
        }

        return false;
    }
}
