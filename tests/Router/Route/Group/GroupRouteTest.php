<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route\Group;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Router\Route\Group\Exception\AssembleAbstractGroupRoute;
use ExtendsSoftware\ExaPHP\Router\Route\RouteInterface;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatchInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class GroupRouteTest extends TestCase
{
    /**
     * Child route.
     *
     * Test that group route will match child route for request and return RouteMatchInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Group\GroupRoute::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Group\GroupRoute::match()
     * @covers \ExtendsSoftware\ExaPHP\Router\Routes::addRoute()
     * @covers \ExtendsSoftware\ExaPHP\Router\Routes::matchRoutes()
     */
    public function testChildRoute(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $match2 = $this->createMock(RouteMatchInterface::class);

        $route2 = $this->createMock(RouteInterface::class);
        $route2
            ->expects($this->once())
            ->method('match')
            ->with($request, 5)
            ->willReturn($match2);

        $match1 = $this->createMock(RouteMatchInterface::class);
        $match1
            ->expects($this->once())
            ->method('getPathOffset')
            ->willReturn(5);

        $match1
            ->expects($this->once())
            ->method('merge')
            ->with($match2)
            ->willReturn(
                $this->createMock(RouteMatchInterface::class)
            );

        $route1 = $this->createMock(RouteInterface::class);
        $route1
            ->expects($this->once())
            ->method('match')
            ->with($request, 5)
            ->willReturn($match1);

        /**
         * @var RouteInterface   $route1
         * @var RouteInterface   $route2
         * @var RequestInterface $request
         */
        $group = new GroupRoute($route1);
        $matched = $group
            ->addRoute($route2, 'route2')
            ->match($request, 5, 'index');

        $this->assertInstanceOf(RouteMatchInterface::class, $matched);
    }

    /**
     * Non-abstract route.
     *
     * Test that group route will match non-abstract route for request and return RouteMatchInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Group\GroupRoute::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Group\GroupRoute::match()
     * @covers \ExtendsSoftware\ExaPHP\Router\Routes::addRoute()
     * @covers \ExtendsSoftware\ExaPHP\Router\Routes::matchRoutes()
     */
    public function testNonAbstractRoute(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $match = $this->createMock(RouteMatchInterface::class);

        $route = $this->createMock(RouteInterface::class);
        $route
            ->expects($this->once())
            ->method('match')
            ->with($request, 0)
            ->willReturn($match);

        /**
         * @var RouteInterface   $route
         * @var RequestInterface $request
         */
        $group = new GroupRoute($route, false);
        $matched = $group->match($request, 0, 'index');

        $this->assertSame($match, $matched);
    }

    /**
     * Abstract route.
     *
     * Test that group route will not match abstract self and return null.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Group\GroupRoute::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Group\GroupRoute::match()
     * @covers \ExtendsSoftware\ExaPHP\Router\Routes::addRoute()
     * @covers \ExtendsSoftware\ExaPHP\Router\Routes::matchRoutes()
     */
    public function testAbstractRoute(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $match = $this->createMock(RouteMatchInterface::class);

        $route = $this->createMock(RouteInterface::class);
        $route
            ->expects($this->once())
            ->method('match')
            ->with($request, 0)
            ->willReturn($match);

        /**
         * @var RouteInterface   $route
         * @var RequestInterface $request
         */
        $group = new GroupRoute($route);
        $matched = $group->match($request, 0, 'index');

        $this->assertNull($matched);
    }

    /**
     * No route match.
     *
     * Test that inner route will not match and return null.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Group\GroupRoute::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Group\GroupRoute::match()
     * @covers \ExtendsSoftware\ExaPHP\Router\Routes::addRoute()
     * @covers \ExtendsSoftware\ExaPHP\Router\Routes::matchRoutes()
     */
    public function testNoRouteMatch(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $route = $this->createMock(RouteInterface::class);
        $route
            ->expects($this->once())
            ->method('match')
            ->with($request, 0)
            ->willReturn(null);

        /**
         * @var RouteInterface   $route
         * @var RequestInterface $request
         */
        $group = new GroupRoute($route);
        $matched = $group->match($request, 0, 'index');

        $this->assertNull($matched);
    }

    /**
     * Assemble.
     *
     * Test that group route will pass assemble to child route.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Group\GroupRoute::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Group\GroupRoute::assemble()
     */
    public function testAssemble(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $route = $this->createMock(RouteInterface::class);
        $route
            ->expects($this->once())
            ->method('assemble')
            ->with($request, [], ['qux' => 'quux'])
            ->willReturn($request);

        /**
         * @var RouteInterface   $route
         * @var RequestInterface $request
         */
        $group = new GroupRoute($route, false);
        $group->assemble($request, [], [
            'qux' => 'quux',
        ]);
    }

    /**
     * Assemble.
     *
     * Test that group route will pass assemble to child route and sub route.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Group\GroupRoute::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Group\GroupRoute::assemble()
     */
    public function testAssembleChildRoute(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $route = $this->createMock(GroupRoute::class);
        $route
            ->expects($this->exactly(2))
            ->method('assemble')
            ->withConsecutive(
                [$request, ['bar', 'qux'], ['qux' => 'quux']],
                [$request, ['qux'], ['qux' => 'quux']]
            )
            ->willReturn($request);

        /**
         * @var RouteInterface   $route
         * @var RequestInterface $request
         */
        $group = new GroupRoute($route, false);
        $group
            ->addRoute($route, 'bar')
            ->assemble($request, [
                'bar',
                'qux',
            ], [
                'qux' => 'quux',
            ]);
    }

    /**
     * Assemble.
     *
     * Test that abstract group route can not be assembled and an exception will be thrown.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Group\GroupRoute::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Group\GroupRoute::assemble()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Group\Exception\AssembleAbstractGroupRoute::__construct()
     */
    public function testAssembleAbstractRoute(): void
    {
        $this->expectException(AssembleAbstractGroupRoute::class);
        $this->expectExceptionMessage('Can not assemble a abstract route.');

        $request = $this->createMock(RequestInterface::class);

        $route = $this->createMock(RouteInterface::class);
        $route
            ->expects($this->once())
            ->method('assemble')
            ->with($request, [], ['qux' => 'quux'])
            ->willReturn($request);

        /**
         * @var RouteInterface   $route
         * @var RequestInterface $request
         */
        $group = new GroupRoute($route);
        $group->assemble($request, [], [
            'qux' => 'quux',
        ]);
    }

    /**
     * Factory.
     *
     * Test that factory will return an instance of RouteInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Group\GroupRoute::factory()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Group\GroupRoute::__construct()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $route = GroupRoute::factory(GroupRoute::class, $serviceLocator, [
            'route' => $this->createMock(RouteInterface::class),
        ]);

        $this->assertInstanceOf(RouteInterface::class, $route);
    }
}
