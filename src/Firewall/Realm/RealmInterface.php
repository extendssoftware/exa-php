<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Firewall\Realm;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;

interface RealmInterface
{
    /**
     * If this realm can verify request.
     *
     * @param RequestInterface $request
     *
     * @return bool
     */
    public function canVerify(RequestInterface $request): bool;

    /**
     * If request is allowed by realm.
     *
     * @param RequestInterface $request
     *
     * @return bool
     */
    public function isAllowed(RequestInterface $request): bool;
}
