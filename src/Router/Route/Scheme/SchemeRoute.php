<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route\Scheme;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Router\Route\RouteInterface;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatch;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatchInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class SchemeRoute implements RouteInterface, StaticFactoryInterface
{
    /**
     * Create a new scheme route.
     *
     * @param string  $scheme
     * @param mixed[] $parameters
     */
    public function __construct(private string $scheme, private readonly array $parameters = [])
    {
        $this->scheme = strtoupper(trim($scheme));
    }

    /**
     * @inheritDoc
     */
    public static function factory(
        string                  $key,
        ServiceLocatorInterface $serviceLocator,
        array                   $extra = null
    ): RouteInterface {
        /** @phpstan-ignore-next-line */
        return new SchemeRoute($extra['scheme'], $extra['parameters'] ?? []);
    }

    /**
     * @inheritDoc
     */
    public function match(RequestInterface $request, int $pathOffset): ?RouteMatchInterface
    {
        if (strtoupper($request
                ->getUri()
                ->getScheme()) === $this->scheme) {
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
                ->withScheme($this->scheme)
        );
    }
}
