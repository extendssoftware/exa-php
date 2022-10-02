<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route\Group;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Router\Route\Group\Exception\AssembleAbstractGroupRoute;
use ExtendsSoftware\ExaPHP\Router\Route\RouteInterface;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatchInterface;
use ExtendsSoftware\ExaPHP\Router\Routes;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\StaticFactory\StaticFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class GroupRoute implements RouteInterface, StaticFactoryInterface
{
    use Routes;

    /**
     * Create a group route.
     *
     * @param RouteInterface $route
     * @param bool           $abstract
     */
    public function __construct(private readonly RouteInterface $route, private readonly bool $abstract = true)
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
        return new GroupRoute($extra['route'], $extra['abstract'] ?? true);
    }

    /**
     * @inheritDoc
     */
    public function match(RequestInterface $request, int $pathOffset): ?RouteMatchInterface
    {
        $outer = $this->route->match($request, $pathOffset);
        if (!$outer instanceof RouteMatchInterface) {
            return null;
        }

        $inner = $this->matchRoutes($request, $outer->getPathOffset());
        if ($inner instanceof RouteMatchInterface) {
            return $outer->merge($inner);
        }

        if (!$this->abstract) {
            return $outer;
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function assemble(RequestInterface $request, array $path, array $parameters): RequestInterface
    {
        $request = $this->route->assemble($request, $path, $parameters);
        if (empty($path)) {
            if ($this->abstract) {
                throw new AssembleAbstractGroupRoute();
            }

            return $request;
        }

        return $this
            ->getRoute(array_shift($path), !empty($path))
            ->assemble($request, $path, $parameters);
    }
}
