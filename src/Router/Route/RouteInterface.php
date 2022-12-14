<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;

interface RouteInterface
{
    /**
     * Match route against request.
     *
     * Parameter pathOffset is used to pass the request uri path offset to other routes.
     *
     * @param RequestInterface $request
     * @param int              $pathOffset
     * @param string           $name
     *
     * @return ?RouteMatchInterface
     * @throws RouteException
     */
    public function match(RequestInterface $request, int $pathOffset, string $name): ?RouteMatchInterface;

    /**
     * Assemble path into request.
     *
     * An exception will be thrown when routes for path can not be found.
     *
     * @param RequestInterface $request
     * @param mixed[]          $path
     * @param mixed[]          $parameters
     *
     * @return RequestInterface
     */
    public function assemble(RequestInterface $request, array $path, array $parameters): RequestInterface;
}
