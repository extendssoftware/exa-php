<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\RateLimiting\Framework\Http;

use ExtendsSoftware\ExaPHP\Authorization\Permission\PermissionInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;
use ExtendsSoftware\ExaPHP\Identity\Storage\StorageInterface;
use ExtendsSoftware\ExaPHP\RateLimiting\Framework\Http\Middleware\RateLimitingMiddleware;
use ExtendsSoftware\ExaPHP\RateLimiting\Framework\ProblemDetails\TooManyRequestsProblemDetails;
use ExtendsSoftware\ExaPHP\RateLimiting\Quota\QuotaInterface;
use ExtendsSoftware\ExaPHP\RateLimiting\RateLimiterInterface;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatchInterface;
use PHPUnit\Framework\TestCase;

class RateLimitingMiddlewareTest extends TestCase
{
    /**
     * Process.
     *
     * Test that permission will be consumed for identity and response with rate limit headers will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\RateLimiting\Framework\Http\Middleware\RateLimitingMiddleware::__construct()
     * @covers \ExtendsSoftware\ExaPHP\RateLimiting\Framework\Http\Middleware\RateLimitingMiddleware::process()
     * @covers \ExtendsSoftware\ExaPHP\RateLimiting\Framework\Http\Middleware\RateLimitingMiddleware::addRateLimitHeaders()
     */
    public function testProcess(): void
    {
        $identity = $this->createMock(IdentityInterface::class);

        $quota = $this->createMock(QuotaInterface::class);
        $quota
            ->expects($this->once())
            ->method('isConsumed')
            ->willReturn(true);

        $quota
            ->expects($this->once())
            ->method('getLimit')
            ->willReturn(10);

        $quota
            ->expects($this->once())
            ->method('getRemaining')
            ->willReturn(5);

        $quota
            ->expects($this->once())
            ->method('getReset')
            ->willReturn(1666697250);

        $rateLimiter = $this->createMock(RateLimiterInterface::class);
        $rateLimiter
            ->expects($this->once())
            ->method('consume')
            ->with(
                $this->callback(function (PermissionInterface $permission): bool {
                    $this->assertSame('foo/bar/baz', $permission->getNotation());

                    return true;
                }),
                $identity
            )
            ->willReturn($quota);

        $storage = $this->createMock(StorageInterface::class);
        $storage
            ->expects($this->once())
            ->method('getIdentity')
            ->willReturn($identity);

        $routeMatch = $this->createMock(RouteMatchInterface::class);

        $routeMatch
            ->expects($this->once())
            ->method('getName')
            ->willReturn('foo/bar/baz');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('routeMatch')
            ->willReturn($routeMatch);

        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects($this->exactly(3))
            ->method('andHeader')
            ->willReturnOnConsecutiveCalls(
                ['X-RateLimit-Limit', 10],
                ['X-RateLimit-Remaining', 5],
                ['X-RateLimit-Reset', 1666697250]
            )
            ->willReturnSelf();

        $chain = $this->createMock(MiddlewareChainInterface::class);
        $chain
            ->expects($this->once())
            ->method('proceed')
            ->with($request)
            ->willReturn($response);

        /**
         * @var RateLimiterInterface     $rateLimiter
         * @var StorageInterface         $storage
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new RateLimitingMiddleware($rateLimiter, $storage);

        $this->assertSame($response, $middleware->process($request, $chain));
    }

    /**
     * Not consumed.
     *
     * Test that permission can not be consumed for identity and response with problem details will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\RateLimiting\Framework\Http\Middleware\RateLimitingMiddleware::__construct()
     * @covers \ExtendsSoftware\ExaPHP\RateLimiting\Framework\Http\Middleware\RateLimitingMiddleware::process()
     * @covers \ExtendsSoftware\ExaPHP\RateLimiting\Framework\Http\Middleware\RateLimitingMiddleware::addRateLimitHeaders()
     */
    public function testNotConsumed(): void
    {
        $identity = $this->createMock(IdentityInterface::class);

        $quota = $this->createMock(QuotaInterface::class);
        $quota
            ->expects($this->once())
            ->method('isConsumed')
            ->willReturn(false);

        $quota
            ->expects($this->exactly(2))
            ->method('getLimit')
            ->willReturn(10);

        $quota
            ->expects($this->exactly(2))
            ->method('getRemaining')
            ->willReturn(0);

        $quota
            ->expects($this->exactly(2))
            ->method('getReset')
            ->willReturn(1666697250);

        $rateLimiter = $this->createMock(RateLimiterInterface::class);
        $rateLimiter
            ->expects($this->once())
            ->method('consume')
            ->with(
                $this->callback(function (PermissionInterface $permission): bool {
                    $this->assertSame('foo/bar/baz', $permission->getNotation());

                    return true;
                }),
                $identity
            )
            ->willReturn($quota);

        $storage = $this->createMock(StorageInterface::class);
        $storage
            ->expects($this->once())
            ->method('getIdentity')
            ->willReturn($identity);

        $routeMatch = $this->createMock(RouteMatchInterface::class);

        $routeMatch
            ->expects($this->once())
            ->method('getName')
            ->willReturn('foo/bar/baz');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('routeMatch')
            ->willReturn($routeMatch);

        $response = $this->createMock(ResponseInterface::class);

        $chain = $this->createMock(MiddlewareChainInterface::class);
        $chain
            ->expects($this->never())
            ->method('proceed');

        /**
         * @var RateLimiterInterface     $rateLimiter
         * @var StorageInterface         $storage
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new RateLimitingMiddleware($rateLimiter, $storage);
        $response = $middleware->process($request, $chain);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertInstanceOf(TooManyRequestsProblemDetails::class, $response->getBody());
    }
}
