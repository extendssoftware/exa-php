<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authentication\Realm;

use ExtendsSoftware\ExaPHP\Authentication\Header\HeaderInterface;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;

interface RealmInterface
{
    /**
     * If this realm can authenticate header.
     *
     * @param HeaderInterface $header
     *
     * @return bool
     */
    public function canAuthenticate(HeaderInterface $header): bool;

    /**
     * Get authentication information for header.
     *
     * When authentication fails, an exception will be thrown.
     *
     * @param HeaderInterface $header
     *
     * @return IdentityInterface|null
     */
    public function authenticate(HeaderInterface $header): ?IdentityInterface;
}
