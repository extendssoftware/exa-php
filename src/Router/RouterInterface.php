<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Router\Route\Match\RouteMatchInterface;

interface RouterInterface
{
    /**
     * Match request to route.
     *
     * @param RequestInterface $request
     *
     * @return RouteMatchInterface
     * @throws RouterException When failed to match a route.
     */
    public function route(RequestInterface $request): RouteMatchInterface;

    /**
     * Assemble route into request.
     *
     * @param string                         $name       The name of the route.
     * @param array<string, string|int>|null $parameters The parameters for the route.
     *
     * @return RequestInterface
     * @throws RouterException
     */
    public function assemble(string $name, array $parameters = null): RequestInterface;
}
