<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Request\Uri\UriInterface;
use ExtendsSoftware\ExaPHP\Router\Exception\InvalidRoutePath;
use ExtendsSoftware\ExaPHP\Router\Exception\NotFound;
use ExtendsSoftware\ExaPHP\Router\Route\Group\GroupRoute;
use ExtendsSoftware\ExaPHP\Router\Route\RouteInterface;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatchInterface;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    /**
     * Match.
     *
     * Test that router can match route and return RouteMatchInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::addRoute()
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::route()
     * @covers \ExtendsSoftware\ExaPHP\Router\Routes::addRoute()
     * @covers \ExtendsSoftware\ExaPHP\Router\Routes::matchRoutes()
     */
    public function testMatch(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('/foo');

        $uri
            ->expects($this->once())
            ->method('getQuery')
            ->willReturn([
                'foo' => 'bar',
            ]);

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        $match = $this->createMock(RouteMatchInterface::class);
        $match
            ->expects($this->once())
            ->method('getPathOffset')
            ->willReturn(4);

        $match
            ->expects($this->once())
            ->method('getParameters')
            ->willReturn([
                'foo' => 'bar',
            ]);

        $route = $this->createMock(RouteInterface::class);
        $route
            ->expects($this->once())
            ->method('match')
            ->with($request)
            ->willReturn($match);

        /**
         * @var RouteInterface   $route
         * @var RequestInterface $request
         */
        $router = new Router();
        $matched = $router
            ->addRoute($route, 'route')
            ->route($request);

        $this->assertSame($match, $matched);
    }

    /**
     * No match.
     *
     * Test that router can not match route and will return null.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::route()
     * @covers \ExtendsSoftware\ExaPHP\Router\Routes::matchRoutes()
     * @covers \ExtendsSoftware\ExaPHP\Router\Exception\NotFound::getRequest()
     */
    public function testNoMatch(): void
    {
        $request = $this->createMock(RequestInterface::class);

        /**
         * @var RequestInterface $request
         */
        $router = new Router();

        try {
            $router->route($request);
        } catch (NotFound $exception) {
            $this->assertSame($request, $exception->getRequest());
        }
    }

    /**
     * Path offset mismatch.
     *
     * Test that a partial URI path can not be matched.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::route()
     * @covers \ExtendsSoftware\ExaPHP\Router\Routes::matchRoutes()
     * @covers \ExtendsSoftware\ExaPHP\Router\Exception\NotFound::__construct()
     */
    public function testPathOffsetMismatch(): void
    {
        $this->expectException(NotFound::class);
        $this->expectExceptionMessage('Request could not be matched by a route.');

        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('/foo/bar');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        $match = $this->createMock(RouteMatchInterface::class);
        $match
            ->expects($this->once())
            ->method('getPathOffset')
            ->willReturn(4);

        $route = $this->createMock(RouteInterface::class);
        $route
            ->expects($this->once())
            ->method('match')
            ->with($request)
            ->willReturn($match);

        /**
         * @var RouteInterface   $route
         * @var RequestInterface $request
         */
        $router = new Router();
        $matched = $router
            ->addRoute($route, 'route')
            ->route($request);

        $this->assertSame($match, $matched);
    }

    /**
     * Too much query parameters.
     *
     * Test that more than the allowed query string parameters will return in an exception.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::route()
     * @covers \ExtendsSoftware\ExaPHP\Router\Routes::matchRoutes()
     * @covers \ExtendsSoftware\ExaPHP\Router\Exception\NotFound::__construct()
     */
    public function testTooMuchQueryParameters(): void
    {
        $this->expectException(NotFound::class);
        $this->expectExceptionMessage('Request could not be matched by a route.');

        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('/foo');

        $uri
            ->expects($this->once())
            ->method('getQuery')
            ->willReturn([
                'foo' => 'bar',
                'qux' => 'quux',
            ]);

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        $match = $this->createMock(RouteMatchInterface::class);
        $match
            ->expects($this->once())
            ->method('getPathOffset')
            ->willReturn(4);

        $match
            ->expects($this->once())
            ->method('getParameters')
            ->willReturn([
                'foo' => 'bar',
            ]);

        $route = $this->createMock(RouteInterface::class);
        $route
            ->expects($this->once())
            ->method('match')
            ->with($request)
            ->willReturn($match);

        /**
         * @var RouteInterface   $route
         * @var RequestInterface $request
         */
        $router = new Router();
        $router
            ->addRoute($route, 'route')
            ->route($request);
    }

    /**
     * Assemble.
     *
     * Test that route will be assembled and request will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::assemble()
     */
    public function testAssemble(): void
    {
        $route = $this->createMock(GroupRoute::class);
        $route
            ->expects($this->once())
            ->method('assemble')
            ->with(
                $this->isInstanceOf(RequestInterface::class),
                ['bar', 'baz'],
                ['foo' => 'bar']
            )
            ->willReturn($this->createMock(RequestInterface::class));

        /**
         * @var RouteInterface $route
         */
        $router = new Router();
        $request = $router
            ->addRoute($route, 'foo')
            ->assemble('foo/bar/baz', ['foo' => 'bar']);

        $this->assertIsObject($request);
    }

    /**
     * Invalid route path.
     *
     * Test that exception will be thrown when route path is invalid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::assemble()
     * @covers \ExtendsSoftware\ExaPHP\Router\Exception\InvalidRoutePath::__construct()
     */
    public function testInvalidRoutePath(): void
    {
        $this->expectException(InvalidRoutePath::class);
        $this->expectExceptionMessage('Invalid router assemble path, got "/foo/".');

        $router = new Router();
        $router->assemble('/foo/');
    }
}
