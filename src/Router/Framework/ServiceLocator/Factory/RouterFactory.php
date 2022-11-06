<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Framework\ServiceLocator\Factory;

use ExtendsSoftware\ExaPHP\Router\Route\Group\GroupRoute;
use ExtendsSoftware\ExaPHP\Router\Route\RouteInterface;
use ExtendsSoftware\ExaPHP\Router\Router;
use ExtendsSoftware\ExaPHP\Router\RouterInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\ServiceFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class RouterFactory implements ServiceFactoryInterface
{
    /**
     * Create router.
     *
     * @param string                  $key
     * @param ServiceLocatorInterface $serviceLocator
     * @param mixed[]|null            $extra
     *
     * @return RouterInterface
     * @throws ServiceLocatorException
     */
    public function createService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        $config = $serviceLocator->getContainer()->get(RouterInterface::class, []);
        $router = new Router();
        foreach ($config['routes'] ?? [] as $name => $config) {
            $router->addRoute(
                $this->createRoute($serviceLocator, $config),
                $name
            );
        }

        return $router;
    }

    /**
     * Create RouterInterface from config.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param mixed[]                 $config
     *
     * @return RouteInterface
     * @throws ServiceLocatorException
     */
    private function createRoute(ServiceLocatorInterface $serviceLocator, array $config): RouteInterface
    {
        /** @var RouteInterface $route */
        $route = $serviceLocator->getService($config['name'], $config['options'] ?? []);
        if (array_key_exists('children', $config)) {
            $route = $this->createGroup($serviceLocator, $route, $config['children'], $config['abstract'] ?? null);
        }

        return $route;
    }

    /**
     * Create group route.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param RouteInterface          $route
     * @param mixed[]                 $children
     * @param bool|null               $abstract
     *
     * @return RouteInterface
     * @throws ServiceLocatorException
     */
    private function createGroup(
        ServiceLocatorInterface $serviceLocator,
        RouteInterface $route,
        array $children,
        bool $abstract = null
    ): RouteInterface {
        /** @var GroupRoute $group */
        $group = $serviceLocator->getService(GroupRoute::class, [
            'route' => $route,
            'abstract' => $abstract,
        ]);

        foreach ($children as $name => $child) {
            $group->addRoute(
                $this->createRoute($serviceLocator, $child),
                $name
            );
        }

        return $group;
    }
}
