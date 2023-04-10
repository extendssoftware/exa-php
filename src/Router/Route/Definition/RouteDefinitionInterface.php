<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route\Definition;

use ExtendsSoftware\ExaPHP\Router\Route\RouteInterface;
use ReflectionClass;
use ReflectionMethod;

interface RouteDefinitionInterface
{
    /**
     * Get route.
     *
     * @return RouteInterface
     */
    public function getRoute(): RouteInterface;

    /**
     * Get reflection class.
     *
     * @return ReflectionClass
     */
    public function getReflectionClass(): ReflectionClass;

    /**
     * Get reflection method.
     *
     * @return ReflectionMethod
     */
    public function getReflectionMethod(): ReflectionMethod;
}
