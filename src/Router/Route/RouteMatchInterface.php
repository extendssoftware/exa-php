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
