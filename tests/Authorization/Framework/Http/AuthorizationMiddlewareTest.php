<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authorization\Framework\Http;

use ExtendsSoftware\ExaPHP\Authorization\AuthorizerInterface;
use ExtendsSoftware\ExaPHP\Authorization\Framework\Http\Middleware\AuthorizationMiddleware;
use ExtendsSoftware\ExaPHP\Authorization\Framework\ProblemDetails\ForbiddenProblemDetails;
use ExtendsSoftware\ExaPHP\Authorization\Permission\PermissionInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatchInterface;
use PHPUnit\Framework\TestCase;

class AuthorizationMiddlewareTest extends TestCase
{
    /**
     * Process.
     *
     * Test that permissions and roles route match parameters will be used for authorization.
     *
     * @covers \ExtendsSoftware\ExaPHP\Authorization\Framework\Http\Middleware\AuthorizationMiddleware::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Authorization\Framework\Http\Middleware\AuthorizationMiddleware::process()
     */
    public function testProcess(): void
    {
        $identity = $this->createMock(IdentityInterface::class);

        $authorizer = $this->createMock(AuthorizerInterface::class);
        $authorizer
            ->expects($this->once())
            ->method('isPermitted')
            ->with(
                $this->callback(function (PermissionInterface $permission): bool {
                    $this->assertSame('foo/bar/baz', $permission->getNotation());

                    return true;
                }),
                $identity
            )
            ->willReturn(true);

        $routeMatch = $this->createMock(RouteMatchInterface::class);

        $routeMatch
            ->expects($this->once())
            ->method('getParameter')
            ->with('permission')
            ->willReturn('foo/bar/baz');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->exactly(2))
            ->method('getAttribute')
            ->withConsecutive(
                ['routeMatch'],
                ['identity']
            )
            ->willReturnOnConsecutiveCalls($routeMatch, $identity);

        $response = $this->createMock(ResponseInterface::class);

        $chain = $this->createMock(MiddlewareChainInterface::class);
        $chain
            ->expects($this->once())
            ->method('proceed')
            ->with($request)
            ->willReturn($response);

        /**
         * @var AuthorizerInterface      $authorizer
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new AuthorizationMiddleware($authorizer);

        $this->assertSame($response, $middleware->process($request, $chain));
    }

    /**
     * Unauthorized.
     *
     * Test that the correct response will be returned when request can not be authorized.
     *
     * @covers \ExtendsSoftware\ExaPHP\Authorization\Framework\Http\Middleware\AuthorizationMiddleware::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Authorization\Framework\Http\Middleware\AuthorizationMiddleware::process()
     */
    public function testForbidden(): void
    {
        $identity = $this->createMock(IdentityInterface::class);

        $authorizer = $this->createMock(AuthorizerInterface::class);
        $authorizer
            ->expects($this->once())
            ->method('isPermitted')
            ->with(
                $this->callback(function (PermissionInterface $permission): bool {
                    $this->assertSame('foo/bar/baz', $permission->getNotation());

                    return true;
                }),
                $identity
            )
            ->willReturn(false);

        $routeMatch = $this->createMock(RouteMatchInterface::class);

        $routeMatch
            ->expects($this->once())
            ->method('getParameter')
            ->with('permission')
            ->willReturn('foo/bar/baz');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->exactly(2))
            ->method('getAttribute')
            ->withConsecutive(
                ['routeMatch'],
                ['identity']
            )
            ->willReturnOnConsecutiveCalls($routeMatch, $identity);

        $chain = $this->createMock(MiddlewareChainInterface::class);

        /**
         * @var AuthorizerInterface      $authorizer
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new AuthorizationMiddleware($authorizer);
        $response = $middleware->process($request, $chain);

        $this->assertInstanceOf(ForbiddenProblemDetails::class, $response->getBody());
    }
}
