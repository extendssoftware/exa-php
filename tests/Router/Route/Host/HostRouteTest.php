<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route\Host;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Request\Uri\UriInterface;
use ExtendsSoftware\ExaPHP\Router\Route\RouteInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class HostRouteTest extends TestCase
{
    /**
     * Match.
     *
     * Test that host route can match host and return RouteMatchInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Host\HostRoute::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Host\HostRoute::match()
     */
    public function testMatch(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getHost')
            ->willReturn('www.example.com');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        /**
         * @var RequestInterface $request
         */
        $host = new HostRoute('www.example.com', [
            'foo' => 'bar',
        ]);
        $match = $host->match($request, 5, 'index');

        $this->assertIsObject($match);
        $this->assertSame(5, $match->getPathOffset());
        $this->assertSame([
            'foo' => 'bar',
        ], $match->getParameters());
    }

    /**
     * Match without parameters.
     *
     * Test that host route can match host and return RouteMatchInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Host\HostRoute::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Host\HostRoute::match()
     */
    public function testMatchWithoutParameters(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getHost')
            ->willReturn('www.example.com');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        /**
         * @var RequestInterface $request
         */
        $host = new HostRoute('www.example.com');
        $match = $host->match($request, 5, 'index');

        $this->assertIsObject($match);
        $this->assertSame(5, $match->getPathOffset());
        $this->assertSame([], $match->getParameters());
    }

    /**
     * No match.
     *
     * Test that host route can not match host and return null.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Host\HostRoute::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Host\HostRoute::match()
     */
    public function testNoMatch(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getHost')
            ->willReturn('www.example.com');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        /**
         * @var RequestInterface $request
         */
        $host = new HostRoute('www.example.net');
        $match = $host->match($request, 5, 'index');

        $this->assertNull($match);
    }

    /**
     * Assemble.
     *
     * Test that host will be set on request URI.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Host\HostRoute::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Host\HostRoute::assemble()
     */
    public function testAssemble(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('withHost')
            ->with('www.example.com')
            ->willReturnSelf();

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        $request
            ->expects($this->once())
            ->method('withUri')
            ->with($uri)
            ->willReturnSelf();

        /**
         * @var RequestInterface $request
         */
        $host = new HostRoute('www.example.com', [
            'foo' => 'bar',
        ]);
        $host->assemble($request, [], []);
    }

    /**
     * Factory.
     *
     * Test that factory will return an instance of RouteInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Host\HostRoute::factory()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Host\HostRoute::__construct()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $route = HostRoute::factory(HostRoute::class, $serviceLocator, [
            'host' => 'www.example.com',
            'parameters' => [
                'foo' => 'bar',
            ],
        ]);

        $this->assertInstanceOf(RouteInterface::class, $route);
    }
}
