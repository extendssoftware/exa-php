<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route\Match;

use ExtendsSoftware\ExaPHP\Router\Route\Definition\RouteDefinitionInterface;

readonly class RouteMatch implements RouteMatchInterface
{
    /**
     * RouteMatch constructor.
     *
     * @param RouteDefinitionInterface $definition
     * @param array<string, string>    $parameters
     */
    public function __construct(private RouteDefinitionInterface $definition, private array $parameters)
    {
    }

    /**
     * @inheritDoc
     */
    public function getDefinition(): RouteDefinitionInterface
    {
        return $this->definition;
    }

    /**
     * @inheritDoc
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @inheritDoc
     */
    public function getParameter(string $name, mixed $default = null): mixed
    {
        return $this->parameters[$name] ?? $default;
    }
}
