<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router;

use ExtendsSoftware\ExaPHP\Http\Request\Request;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Router\Exception\InvalidRoutePath;
use ExtendsSoftware\ExaPHP\Router\Exception\NotFound;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatchInterface;
use function array_merge;

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
    public function assemble(RouteMatchInterface|string $name, array $parameters = null): RequestInterface
    {
        if ($name instanceof RouteMatchInterface) {
            $parameters = array_merge(
                $name->getParameters(),
                $parameters ?? []
            );
            $name = $name->getName();
        }

        if (preg_match($this->pattern, $name) === 0) {
            throw new InvalidRoutePath($name);
        }

        $routes = explode('/', $name);
        $route = $this->getRoute(array_shift($routes), !empty($routes));

        return $route->assemble(new Request(), $routes, $parameters ?? []);
    }
}
