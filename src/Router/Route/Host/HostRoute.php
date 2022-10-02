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
     * Create a method route.
     *
     * @param string  $host
     * @param mixed[] $parameters
     */
    public function __construct(private readonly string $host, private readonly array $parameters = [])
    {
    }

    /**
     * @inheritDoc
     */
    public static function factory(
        string $key,
        ServiceLocatorInterface $serviceLocator,
        array  $extra = null
    ): RouteInterface {
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
