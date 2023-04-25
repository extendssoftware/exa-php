<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Framework\ServiceLocator\Factory;

use ExtendsSoftware\ExaPHP\Router\Route\Definition\RouteDefinition;
use ExtendsSoftware\ExaPHP\Router\Route\Route;
use ExtendsSoftware\ExaPHP\Router\Router;
use ExtendsSoftware\ExaPHP\Router\RouterInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\ServiceFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ReflectionClass;
use ReflectionException;

class RouterFactory implements ServiceFactoryInterface
{
    /**
     * Create router.
     *
     * @param string                  $class
     * @param ServiceLocatorInterface $serviceLocator
     * @param array<mixed>|null       $extra
     *
     * @return RouterInterface
     * @throws ReflectionException
     */
    public function createService(string $class, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        $classes = $serviceLocator->getContainer()->find(RouterInterface::class, []);
        $router = new Router();
        foreach ($classes as $class) {
            $reflectionClass = new ReflectionClass($class);
            foreach ($reflectionClass->getMethods() as $reflectionMethod) {
                foreach ($reflectionMethod->getAttributes(Route::class) as $attribute) {
                    $router->addDefinition(
                        new RouteDefinition($attribute->newInstance(), $reflectionClass, $reflectionMethod)
                    );
                }
            }
        }

        return $router;
    }
}
