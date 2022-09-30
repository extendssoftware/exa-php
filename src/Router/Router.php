<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router;

use ExtendsSoftware\ExaPHP\Http\Request\Request;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Router\Exception\InvalidRoutePath;
use ExtendsSoftware\ExaPHP\Router\Exception\NotFound;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatchInterface;

class Router implements RouterInterface
{
    use Routes;

    /**
     * Pattern for route path.
     *
     * @var string
     */
    private string $pattern = '/^([a-z0-9\-_]+)((?:\/([a-z0-9\-_]+))*)$/i';

    /**
     * @inheritDoc
     */
    public function route(RequestInterface $request): RouteMatchInterface
    {
        $match = $this->matchRoutes($request, 0);
        $uri = $request->getUri();
        if ($match instanceof RouteMatchInterface &&
            $match->getPathOffset() === strlen($uri->getPath()) &&
            empty(array_diff_key($uri->getQuery(), $match->getParameters()))
        ) {
            return $match;
        }

        throw new NotFound($request);
    }

    /**
     * @inheritDoc
     */
    public function assemble(string $path, array $parameters = null): RequestInterface
    {
        if (preg_match($this->pattern, $path) === 0) {
            throw new InvalidRoutePath($path);
        }

        $routes = explode('/', $path);
        $route = $this->getRoute(array_shift($routes), !empty($routes));

        return $route->assemble(new Request(), $routes, $parameters ?? []);
    }
}
