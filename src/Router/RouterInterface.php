<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatchInterface;

interface RouterInterface
{
    /**
     * Route request to corresponding controller.
     *
     * An exception will be thrown when request can not be matched. A route can throw a more detailed exception.
     *
     * @param RequestInterface $request
     *
     * @return RouteMatchInterface
     * @throws RouterException
     */
    public function route(RequestInterface $request): RouteMatchInterface;

    /**
     * Assemble route name into a request.
     *
     * @param RouteMatchInterface|string $name       Consecutive route names separated with a forward slash or matched
     *                                               route to use for name.
     * @param mixed[]|null               $parameters Parameters to use when assembling routes. Will merge into route
     *                                               match parameters if provided.
     *
     * @return RequestInterface
     * @throws RouterException       When $path can not be found.
     */
    public function assemble(RouteMatchInterface|string $name, array $parameters = null): RequestInterface;
}
