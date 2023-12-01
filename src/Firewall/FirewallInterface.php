<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Firewall;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;

interface FirewallInterface
{
    /**
     * If request is allowed by firewall.
     *
     * @param RequestInterface $request
     *
     * @return bool
     */
    public function isAllowed(RequestInterface $request): bool;
}
