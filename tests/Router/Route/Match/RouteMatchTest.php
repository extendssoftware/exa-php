<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route\Match;

use ExtendsSoftware\ExaPHP\Router\Route\Definition\RouteDefinitionInterface;
use PHPUnit\Framework\TestCase;

class RouteMatchTest extends TestCase
{
    /**
     * Get methods.
     *
     * Test that get methods will return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Match\RouteMatch::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Match\RouteMatch::getDefinition()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Match\RouteMatch::getParameters()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Match\RouteMatch::getParameter()
     */
    public function testGetMethods(): void
    {
        $routeDefinition = $this->createMock(RouteDefinitionInterface::class);

        /**
         * @var RouteDefinitionInterface $routeDefinition
         */
        $match = new RouteMatch($routeDefinition, ['foo' => 'bar']);

        $this->assertSame($routeDefinition, $match->getDefinition());
        $this->assertSame(['foo' => 'bar'], $match->getParameters());
        $this->assertSame('bar', $match->getParameter('foo'));
        $this->assertNull($match->getParameter('bar'));
        $this->assertSame('foo', $match->getParameter('bar', 'foo'));
    }
}
