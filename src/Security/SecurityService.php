<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Security;

use ExtendsSoftware\ExaPHP\Authentication\AuthenticationInfoInterface;
use ExtendsSoftware\ExaPHP\Authentication\AuthenticatorInterface;
use ExtendsSoftware\ExaPHP\Authentication\Header\HeaderInterface;
use ExtendsSoftware\ExaPHP\Authorization\AuthorizerInterface;
use ExtendsSoftware\ExaPHP\Authorization\Permission\Permission;
use ExtendsSoftware\ExaPHP\Authorization\Policy\PolicyInterface;
use ExtendsSoftware\ExaPHP\Authorization\Role\Role;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;
use ExtendsSoftware\ExaPHP\Identity\Storage\StorageInterface;

class SecurityService implements SecurityServiceInterface
{
    /**
     * SecurityService constructor.
     *
     * @param AuthenticatorInterface $authenticator
     * @param AuthorizerInterface    $authorizer
     * @param StorageInterface       $storage
     */
    public function __construct(
        private readonly AuthenticatorInterface $authenticator,
        private readonly AuthorizerInterface    $authorizer,
        private readonly StorageInterface       $storage
    ) {
    }

    /**
     * @inheritDoc
     */
    public function authenticate(HeaderInterface $header): bool
    {
        $info = $this->authenticator->authenticate($header);
        if ($info instanceof AuthenticationInfoInterface) {
            $this->storage->setIdentity($info->getIdentity());

            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function isPermitted(string $permission): bool
    {
        $identity = $this->getIdentity();
        if ($identity instanceof IdentityInterface) {
            return $this->authorizer->isPermitted($identity, new Permission($permission));
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function getIdentity(): ?IdentityInterface
    {
        $identity = $this->storage->getIdentity();
        if ($identity instanceof IdentityInterface) {
            return $identity;
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function hasRole(string $role): bool
    {
        $identity = $this->getIdentity();
        if ($identity instanceof IdentityInterface) {
            return $this->authorizer->hasRole($identity, new Role($role));
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function isAllowed(PolicyInterface $policy): bool
    {
        $identity = $this->getIdentity();
        if ($identity instanceof IdentityInterface) {
            return $this->authorizer->isAllowed($identity, $policy);
        }

        return false;
    }
}
