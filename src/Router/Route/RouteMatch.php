<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route;

class RouteMatch implements RouteMatchInterface
{
    /**
     * Create a route match.
     *
     * @param mixed[] $parameters
     * @param int     $offset
     * @param string  $name
     */
    public function __construct(private array $parameters, private int $offset, private string $name)
    {
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getPathOffset(): int
    {
        return $this->offset;
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
    public function getParameter(string $key, mixed $default = null): mixed
    {
        return $this->parameters[$key] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function merge(RouteMatchInterface $routeMatch): RouteMatchInterface
    {
        $merged = clone $this;
        $merged->name .= '/' . $routeMatch->getName();
        $merged->parameters = array_replace_recursive($this->parameters, $routeMatch->getParameters());
        $merged->offset = $routeMatch->getPathOffset();

        return $merged;
    }
}
