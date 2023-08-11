<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route\Definition;

use ExtendsSoftware\ExaPHP\Router\Route\RouteInterface;
use ReflectionClass;
use ReflectionMethod;

readonly class RouteDefinition implements RouteDefinitionInterface
{
    /**
     * RouteDefinition constructor.
     *
     * @param RouteInterface   $route
     * @param ReflectionClass  $reflectionClass
     * @param ReflectionMethod $reflectionMethod
     */
    public function __construct(
        private RouteInterface   $route,
        private ReflectionClass  $reflectionClass,
        private ReflectionMethod $reflectionMethod,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getRoute(): RouteInterface
    {
        return $this->route;
    }

    /**
     * @inheritDoc
     */
    public function getReflectionClass(): ReflectionClass
    {
        return $this->reflectionClass;
    }

    /**
     * @inheritDoc
     */
    public function getReflectionMethod(): ReflectionMethod
    {
        return $this->reflectionMethod;
    }
}
