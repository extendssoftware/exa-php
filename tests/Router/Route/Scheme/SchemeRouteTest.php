<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route\Scheme;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Request\Uri\UriInterface;
use ExtendsSoftware\ExaPHP\Router\Route\RouteInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class SchemeRouteTest extends TestCase
{
    /**
     * Match.
     *
     * Test that route will match scheme HTTPS and return instance of RouteMatchInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Scheme\SchemeRoute::factory()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Scheme\SchemeRoute::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Scheme\SchemeRoute::match()
     */
    public function testMatch(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getScheme')
            ->willReturn('https');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        /**
         * @var RequestInterface $request
         */
        $scheme = new SchemeRoute('https', [
            'foo' => 'bar',
        ]);
        $match = $scheme->match($request, 5, 'index');

        $this->assertIsObject($match);
        $this->assertSame(5, $match->getPathOffset());
        $this->assertSame([
            'foo' => 'bar',
        ], $match->getParameters());
    }

    /**
     * Match without parameters.
     *
     * Test that route will match scheme HTTPS and return instance of RouteMatchInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Scheme\SchemeRoute::factory()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Scheme\SchemeRoute::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Scheme\SchemeRoute::match()
     */
    public function testMatchWithoutParameters(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getScheme')
            ->willReturn('https');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        /**
         * @var RequestInterface $request
         */
        $scheme = new SchemeRoute('https');
        $match = $scheme->match($request, 5, 'index');

        $this->assertIsObject($match);
        $this->assertSame(5, $match->getPathOffset());
        $this->assertSame([], $match->getParameters());
    }

    /**
     * No match.
     *
     * Test that route will not match scheme HTTP and will return null.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Scheme\SchemeRoute::factory()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Scheme\SchemeRoute::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Scheme\SchemeRoute::match()
     */
    public function testNoMatch(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getScheme')
            ->willReturn('http');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        /**
         * @var RequestInterface $request
         */
        $scheme = new SchemeRoute('https');
        $match = $scheme->match($request, 5, 'index');

        $this->assertNull($match);
    }

    /**
     * Assemble.
     *
     * Test that scheme will be set on request URI.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Scheme\SchemeRoute::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Scheme\SchemeRoute::assemble()
     */
    public function testAssemble(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('withScheme')
            ->with('HTTPS')
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
        $host = new SchemeRoute('https');
        $host->assemble($request, [], []);
    }

    /**
     * Factory.
     *
     * Test that factory will return an instance of RouteInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Scheme\SchemeRoute::factory()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Scheme\SchemeRoute::__construct()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $route = SchemeRoute::factory(SchemeRoute::class, $serviceLocator, [
            'scheme' => 'https',
            'parameters' => [
                'foo' => 'bar',
            ],
        ]);

        $this->assertInstanceOf(RouteInterface::class, $route);
    }
}
