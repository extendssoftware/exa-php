<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Identity\Storage;

use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;

interface StorageInterface
{
    /**
     * Get identity from storage.
     *
     * @return IdentityInterface|null
     */
    public function getIdentity(): ?IdentityInterface;

    /**
     * Set identity to storage.
     *
     * @param IdentityInterface $identity
     *
     * @return StorageInterface
     */
    public function setIdentity(IdentityInterface $identity): StorageInterface;
}
