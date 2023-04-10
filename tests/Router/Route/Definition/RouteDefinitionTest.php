<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route\Definition;

use ExtendsSoftware\ExaPHP\Router\Route\RouteInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

class RouteDefinitionTest extends TestCase
{
    /**
     * Get methods.
     *
     * Test that get methods will return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Definition\RouteDefinition::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Definition\RouteDefinition::getRoute()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Definition\RouteDefinition::getReflectionClass()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Definition\RouteDefinition::getReflectionMethod()
     */
    public function testGetMethods(): void
    {
        $route = $this->createMock(RouteInterface::class);

        $reflectionClass = $this->createMock(ReflectionClass::class);

        $reflectionMethod = $this->createMock(ReflectionMethod::class);

        /**
         * @var RouteInterface $route
         * @var ReflectionClass $reflectionClass
         * @var ReflectionMethod $reflectionMethod
         */
        $routeDefinition = new RouteDefinition($route, $reflectionClass, $reflectionMethod);

        $this->assertSame($route, $routeDefinition->getRoute());
        $this->assertSame($reflectionClass, $routeDefinition->getReflectionClass());
        $this->assertSame($reflectionMethod, $routeDefinition->getReflectionMethod());
    }
}
