<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router;

use Exception;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Router\Exception\GroupRouteExpected;
use ExtendsSoftware\ExaPHP\Router\Exception\RouteNotFound;
use ExtendsSoftware\ExaPHP\Router\Route\Group\GroupRoute;
use ExtendsSoftware\ExaPHP\Router\Route\Method\Exception\MethodNotAllowed;
use ExtendsSoftware\ExaPHP\Router\Route\RouteException;
use ExtendsSoftware\ExaPHP\Router\Route\RouteInterface;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatchInterface;

trait Routes
{
    /**
     * Routes.
     *
     * @var RouteInterface[]
     */
    private array $routes = [];

    /**
     * Add route to all the routes.
     *
     * @param RouteInterface $route
     * @param string         $name
     *
     * @return static
     */
    public function addRoute(RouteInterface $route, string $name): static
    {
        $this->routes[$name] = $route;

        return $this;
    }

    /**
     * Route request to child routes with pathOffset.
     *
     *
     *
     * @param RequestInterface $request
     * @param int              $pathOffset
     *
     * @return RouteMatchInterface|null
     * @throws RouteException|Exception
     */
    private function matchRoutes(RequestInterface $request, int $pathOffset): ?RouteMatchInterface
    {
        $notAllowed = null;
        foreach ($this->routes as $name => $route) {
            try {
                $match = $route->match($request, $pathOffset, $name);
                if ($match instanceof RouteMatchInterface) {
                    return $match;
                }
            } catch (MethodNotAllowed $exception) {
                if ($notAllowed instanceof MethodNotAllowed) {
                    $notAllowed->addAllowedMethods($exception->getAllowedMethods());
                } else {
                    $notAllowed = $exception;
                }
            }
        }

        if ($notAllowed instanceof MethodNotAllowed) {
            throw $notAllowed;
        }

        return null;
    }

    /**
     * Get route for name.
     *
     * @param string    $name       Name of the route.
     * @param bool|null $groupRoute If route must be a GroupRoute (when assembling and a route path is left).
     *
     * @return RouteInterface
     * @throws GroupRouteExpected   When route is not GroupRoute, but was expected to be.
     * @throws RouteNotFound        When route for $name can not be found.
     */
    private function getRoute(string $name, bool $groupRoute = null): RouteInterface
    {
        if (!array_key_exists($name, $this->routes)) {
            throw new RouteNotFound($name);
        }

        $route = $this->routes[$name];
        if ($route instanceof GroupRoute || !$groupRoute) {
            return $route;
        }

        throw new GroupRouteExpected($route);
    }
}
