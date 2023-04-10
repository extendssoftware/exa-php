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
     * @param string                     $name       ...
     * @param array<string, string>|null $parameters ...
     *
     * @return RequestInterface
     * @throws RouterException
     */
    public function assemble(string $name, array $parameters = null): RequestInterface;
}
