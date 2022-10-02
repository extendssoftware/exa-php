<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route;

class RouteMatch implements RouteMatchInterface
{
    /**
     * Create a route match.
     *
     * @param mixed[] $parameters
     * @param int     $pathOffset
     */
    public function __construct(private array $parameters, private int $pathOffset)
    {
    }

    /**
     * @inheritDoc
     */
    public function getPathOffset(): int
    {
        return $this->pathOffset;
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
    public function merge(RouteMatchInterface $routeMatch): RouteMatchInterface
    {
        $merged = clone $this;
        $merged->parameters = array_replace_recursive($this->parameters, $routeMatch->getParameters());
        $merged->pathOffset = $routeMatch->getPathOffset();

        return $merged;
    }
}
