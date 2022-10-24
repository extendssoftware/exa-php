<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authentication\Framework\Http;

use ExtendsSoftware\ExaPHP\Authentication\AuthenticatorInterface;
use ExtendsSoftware\ExaPHP\Authentication\Framework\Http\Middleware\AuthenticationMiddleware;
use ExtendsSoftware\ExaPHP\Authentication\Framework\ProblemDetails\UnauthorizedProblemDetails;
use ExtendsSoftware\ExaPHP\Authentication\Header\HeaderInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;
use ExtendsSoftware\ExaPHP\Identity\Storage\StorageInterface;
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

        $authenticator = $this->createMock(AuthenticatorInterface::class);
        $authenticator
            ->expects($this->once())
            ->method('authenticate')
            ->with($this->callback(function (HeaderInterface $header) {
                $this->assertSame('Bearer', $header->getScheme());
                $this->assertSame('ed6ed1ec-769b-4f35-b74a-d4d4205f1d88', $header->getCredentials());

                return true;
            }))
            ->willReturn($identity);

        $storage = $this->createMock(StorageInterface::class);
        $storage
            ->expects($this->once())
            ->method('setIdentity')
            ->with($identity)
            ->willReturnSelf();

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getHeader')
            ->with('Authorization')
            ->willReturn('Bearer ed6ed1ec-769b-4f35-b74a-d4d4205f1d88');

        $response = $this->createMock(ResponseInterface::class);

        $chain = $this->createMock(MiddlewareChainInterface::class);
        $chain
            ->expects($this->once())
            ->method('proceed')
            ->with($request)
            ->willReturn($response);

        /**
         * @var AuthenticatorInterface   $authenticator
         * @var StorageInterface         $storage
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new AuthenticationMiddleware($authenticator, $storage);

        $this->assertSame($response, $middleware->process($request, $chain));
    }

    /**
     * Authorization header malformed.
     *
     * Test that the correct response will be returned when authorization header is malformed.
     *
     * @covers \ExtendsSoftware\ExaPHP\Authentication\Framework\Http\Middleware\AuthenticationMiddleware::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Authentication\Framework\Http\Middleware\AuthenticationMiddleware::process()
     */
    public function testAuthorizationHeaderMalformed(): void
    {
        $authenticator = $this->createMock(AuthenticatorInterface::class);

        $storage = $this->createMock(StorageInterface::class);

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getHeader')
            ->with('Authorization')
            ->willReturn('Bearer');

        $chain = $this->createMock(MiddlewareChainInterface::class);

        /**
         * @var AuthenticatorInterface   $authenticator
         * @var StorageInterface         $storage
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new AuthenticationMiddleware($authenticator, $storage);
        $response = $middleware->process($request, $chain);

        $this->assertInstanceOf(UnauthorizedProblemDetails::class, $response->getBody());
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
        $authenticator = $this->createMock(AuthenticatorInterface::class);
        $authenticator
            ->expects($this->once())
            ->method('authenticate')
            ->with($this->callback(function (HeaderInterface $header) {
                $this->assertSame('Bearer', $header->getScheme());
                $this->assertSame('ed6ed1ec-769b-4f35-b74a-d4d4205f1d88', $header->getCredentials());

                return true;
            }))
            ->willReturn(null);

        $storage = $this->createMock(StorageInterface::class);

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getHeader')
            ->with('Authorization')
            ->willReturn('Bearer ed6ed1ec-769b-4f35-b74a-d4d4205f1d88');

        $chain = $this->createMock(MiddlewareChainInterface::class);

        /**
         * @var AuthenticatorInterface   $authenticator
         * @var StorageInterface         $storage
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new AuthenticationMiddleware($authenticator, $storage);
        $response = $middleware->process($request, $chain);

        $this->assertInstanceOf(UnauthorizedProblemDetails::class, $response->getBody());
    }
}
