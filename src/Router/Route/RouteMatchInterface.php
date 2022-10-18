<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route;

interface RouteMatchInterface
{
    /**
     * Get route name.
     *
     * @return string Consecutive route names separated with a forward slash.
     */
    public function getName(): string;

    /**
     * Get merged parameters from route.
     *
     * @return mixed[]
     */
    public function getParameters(): array;

    /**
     * Get parameter.
     *
     * @param string     $key     The key to get value for.
     * @param mixed|null $default Default value to return when key not exists.
     *
     * @return mixed
     */
    public function getParameter(string $key, mixed $default = null): mixed;

    /**
     * Get request URI path offset.
     *
     * @return int
     */
    public function getPathOffset(): int;

    /**
     * Merge with other routeMatch.
     *
     * Used for nested routes.
     *
     * @param RouteMatchInterface $routeMatch
     *
     * @return RouteMatchInterface
     */
    public function merge(RouteMatchInterface $routeMatch): RouteMatchInterface;
}
