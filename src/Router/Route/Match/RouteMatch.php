<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route\Match;

use ExtendsSoftware\ExaPHP\Router\Route\Definition\RouteDefinitionInterface;

class RouteMatch implements RouteMatchInterface
{
    /**
     * RouteMatch constructor.
     *
     * @param RouteDefinitionInterface $definition
     * @param array<string, string> $parameters
     */
    public function __construct(
        private readonly RouteDefinitionInterface $definition,
        private readonly array                    $parameters,
    ) {
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
    public function getParameter(string $name): ?string
    {
        return $this->parameters[$name] ?? null;
    }
}
