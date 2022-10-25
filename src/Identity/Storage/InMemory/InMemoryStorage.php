<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Identity\Storage\InMemory;

use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;
use ExtendsSoftware\ExaPHP\Identity\Storage\Exception\IdentityNotSet;
use ExtendsSoftware\ExaPHP\Identity\Storage\StorageInterface;

class InMemoryStorage implements StorageInterface
{
    /**
     * Temporary stored identity.
     *
     * @var IdentityInterface|null
     */
    private ?IdentityInterface $identity = null;

    /**
     * @inheritDoc
     */
    public function getIdentity(): IdentityInterface
    {
        if ($this->identity === null) {
            throw new IdentityNotSet();
        }

        return $this->identity;
    }

    /**
     * @inheritDoc
     */
    public function setIdentity(IdentityInterface $identity): StorageInterface
    {
        $this->identity = $identity;

        return $this;
    }
}
