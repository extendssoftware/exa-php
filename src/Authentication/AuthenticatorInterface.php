<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authentication;

use ExtendsSoftware\ExaPHP\Authentication\Header\HeaderInterface;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;

interface AuthenticatorInterface
{
    /**
     * Authenticate header.
     *
     * An exception will be thrown when authentication fails.
     *
     * @param HeaderInterface $header
     *
     * @return IdentityInterface|null
     */
    public function authenticate(HeaderInterface $header): ?IdentityInterface;
}
