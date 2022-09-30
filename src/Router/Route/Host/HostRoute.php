<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route\Host;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Router\Route\RouteInterface;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatch;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatchInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class HostRoute implements RouteInterface, StaticFactoryInterface
{
    /**
     * Host to match.
     *
     * @var string
     */
    private string $host;

    /**
     * Default parameters to return.
     *
     * @var mixed[]
     */
    private array $parameters;

    /**
     * Create a method route.
     *
     * @param string       $host
     * @param mixed[]|null $parameters
     */
    public function __construct(string $host, array $parameters = null)
    {
        $this->host = $host;
        $this->parameters = $parameters ?? [];
    }

    /**
     * @inheritDoc
     */
    public static function factory(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        /** @phpstan-ignore-next-line */
        return new HostRoute($extra['host'], $extra['parameters'] ?? []);
    }

    /**
     * @inheritDoc
     */
    public function match(RequestInterface $request, int $pathOffset): ?RouteMatchInterface
    {
        if ($this->host === $request
                ->getUri()
                ->getHost()) {
            return new RouteMatch($this->parameters, $pathOffset);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function assemble(RequestInterface $request, array $path, array $parameters): RequestInterface
    {
        return $request->withUri(
            $request
                ->getUri()
                ->withHost($this->host)
        );
    }
}
