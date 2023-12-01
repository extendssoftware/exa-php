<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route\Match;

use ExtendsSoftware\ExaPHP\Router\Route\Definition\RouteDefinitionInterface;

interface RouteMatchInterface
{
    /**
     * Get route definition.
     *
     * @return RouteDefinitionInterface
     */
    public function getDefinition(): RouteDefinitionInterface;

    /**
     * Get matched parameters from route.
     *
     * @return array<string, string>
     */
    public function getParameters(): array;

    /**
     * Get parameter.
     *
     * @param string     $name
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function getParameter(string $name, mixed $default = null): mixed;
}
