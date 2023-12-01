<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authentication;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;

interface AuthenticatorInterface
{
    /**
     * Authenticate header.
     *
     * An exception will be thrown when authentication fails.
     *
     * @param RequestInterface $request
     *
     * @return IdentityInterface|false|null
     */
    public function authenticate(RequestInterface $request): IdentityInterface|false|null;
}
