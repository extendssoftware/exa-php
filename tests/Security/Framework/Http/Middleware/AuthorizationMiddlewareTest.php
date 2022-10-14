<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Security\Framework\Http\Middleware;

use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatchInterface;
use ExtendsSoftware\ExaPHP\Security\Framework\ProblemDetails\ForbiddenProblemDetails;
use ExtendsSoftware\ExaPHP\Security\SecurityServiceInterface;
use PHPUnit\Framework\TestCase;

class AuthorizationMiddlewareTest extends TestCase
{
    /**
     * Process.
     *
     * Test that permissions and roles route match parameters will be used for authorization.
     *
     * @covers \ExtendsSoftware\ExaPHP\Security\Framework\Http\Middleware\AuthorizationMiddleware::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Security\Framework\Http\Middleware\AuthorizationMiddleware::process()
     */
    public function testProcess(): void
    {
        $security = $this->createMock(SecurityServiceInterface::class);
        $security
            ->expects($this->once())
            ->method('isPermitted')
            ->with('foo:bar:baz')
            ->willReturn(true);

        $routeMatch = $this->createMock(RouteMatchInterface::class);
        $routeMatch
            ->expects($this->once())
            ->method('getParameters')
            ->willReturn([
                'permissions' => [
                    'foo:bar:baz',
                ],
            ]);

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('routeMatch')
            ->willReturn($routeMatch);

        $chain = $this->createMock(MiddlewareChainInterface::class);
        $chain
            ->expects($this->once())
            ->method('proceed')
            ->with($request)
            ->willReturn($this->createMock(ResponseInterface::class));

        /**
         * @var SecurityServiceInterface $security
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new AuthorizationMiddleware($security);
        $response = $middleware->process($request, $chain);

        $this->assertIsObject($response);
    }

    /**
     * Unauthorized.
     *
     * Test that the correct response will be returned when request can not be authorized.
     *
     * @covers \ExtendsSoftware\ExaPHP\Security\Framework\Http\Middleware\AuthorizationMiddleware::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Security\Framework\Http\Middleware\AuthorizationMiddleware::process()
     */
    public function testForbidden(): void
    {
        $security = $this->createMock(SecurityServiceInterface::class);
        $security
            ->expects($this->once())
            ->method('isPermitted')
            ->with('foo:bar:baz')
            ->willReturn(false);

        $routeMatch = $this->createMock(RouteMatchInterface::class);
        $routeMatch
            ->expects($this->once())
            ->method('getParameters')
            ->willReturn([
                'permissions' => [
                    'foo:bar:baz',
                ],
            ]);

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('routeMatch')
            ->willReturn($routeMatch);

        $chain = $this->createMock(MiddlewareChainInterface::class);

        /**
         * @var SecurityServiceInterface $security
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new AuthorizationMiddleware($security);
        $response = $middleware->process($request, $chain);

        $this->assertInstanceOf(ForbiddenProblemDetails::class, $response->getBody());
    }
}
