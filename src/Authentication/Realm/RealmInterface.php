<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authentication\Realm;

use ExtendsSoftware\ExaPHP\Authentication\Realm\Exception\AuthenticationFailed;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;

interface RealmInterface
{
    /**
     * If this realm can authenticate header.
     *
     * @param RequestInterface $request
     *
     * @return bool
     */
    public function canAuthenticate(RequestInterface $request): bool;

    /**
     * Get authentication information for header.
     *
     * When authentication fails, an exception will be thrown.
     *
     * @param RequestInterface $request
     *
     * @return IdentityInterface
     * @throws AuthenticationFailed When authentication fails.
     */
    public function authenticate(RequestInterface $request): IdentityInterface;
}
