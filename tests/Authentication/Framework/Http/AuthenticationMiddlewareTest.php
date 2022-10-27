<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authentication\Framework\Http;

use ExtendsSoftware\ExaPHP\Authentication\AuthenticatorInterface;
use ExtendsSoftware\ExaPHP\Authentication\Framework\Http\Middleware\AuthenticationMiddleware;
use ExtendsSoftware\ExaPHP\Authentication\Framework\ProblemDetails\UnauthorizedProblemDetails;
use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;
use PHPUnit\Framework\TestCase;

class AuthenticationMiddlewareTest extends TestCase
{
    /**
     * Process.
     *
     * Test that request can be authenticated and middleware will proceed.
     *
     * @covers \ExtendsSoftware\ExaPHP\Authentication\Framework\Http\Middleware\AuthenticationMiddleware::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Authentication\Framework\Http\Middleware\AuthenticationMiddleware::process()
     */
    public function testProcess(): void
    {
        $identity = $this->createMock(IdentityInterface::class);

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('andAttribute')
            ->with('identity', $identity)
            ->willReturnSelf();

        $authenticator = $this->createMock(AuthenticatorInterface::class);
        $authenticator
            ->expects($this->once())
            ->method('authenticate')
            ->with($request)
            ->willReturn($identity);

        $response = $this->createMock(ResponseInterface::class);

        $chain = $this->createMock(MiddlewareChainInterface::class);
        $chain
            ->expects($this->once())
            ->method('proceed')
            ->with($request)
            ->willReturn($response);

        /**
         * @var AuthenticatorInterface   $authenticator
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new AuthenticationMiddleware($authenticator);

        $this->assertSame($response, $middleware->process($request, $chain));
    }

    /**
     * Unauthorized.
     *
     * Test that the correct response will be returned when request can not be authenticated.
     *
     * @covers \ExtendsSoftware\ExaPHP\Authentication\Framework\Http\Middleware\AuthenticationMiddleware::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Authentication\Framework\Http\Middleware\AuthenticationMiddleware::process()
     */
    public function testUnauthorized(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $authenticator = $this->createMock(AuthenticatorInterface::class);
        $authenticator
            ->expects($this->once())
            ->method('authenticate')
            ->with($request)
            ->willReturn(false);

        $chain = $this->createMock(MiddlewareChainInterface::class);

        /**
         * @var AuthenticatorInterface   $authenticator
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new AuthenticationMiddleware($authenticator);
        $response = $middleware->process($request, $chain);

        $this->assertInstanceOf(UnauthorizedProblemDetails::class, $response->getBody());
    }
}
