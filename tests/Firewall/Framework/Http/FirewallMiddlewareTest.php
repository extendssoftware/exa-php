<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Firewall\Framework\Http;

use ExtendsSoftware\ExaPHP\Firewall\FirewallInterface;
use ExtendsSoftware\ExaPHP\Firewall\Framework\Http\Middleware\FirewallMiddleware;
use ExtendsSoftware\ExaPHP\Firewall\Framework\ProblemDetails\ForbiddenProblemDetails;
use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use PHPUnit\Framework\TestCase;

class FirewallMiddlewareTest extends TestCase
{
    /**
     * Process.
     *
     * Test that request is allowed and middleware will proceed.
     *
     * @covers \ExtendsSoftware\ExaPHP\Firewall\Framework\Http\Middleware\FirewallMiddleware::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Firewall\Framework\Http\Middleware\FirewallMiddleware::process()
     */
    public function testProcess(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $response = $this->createMock(ResponseInterface::class);

        $firewall = $this->createMock(FirewallInterface::class);
        $firewall
            ->expects($this->once())
            ->method('isAllowed')
            ->with($request)
            ->willReturn(true);

        $chain = $this->createMock(MiddlewareChainInterface::class);
        $chain
            ->expects($this->once())
            ->method('proceed')
            ->with($request)
            ->willReturn($response);

        /**
         * @var FirewallInterface        $firewall
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new FirewallMiddleware($firewall);

        $this->assertSame($response, $middleware->process($request, $chain));
    }

    /**
     * Forbidden.
     *
     * Test that the correct response will be returned when request is not allowed.
     *
     * @covers \ExtendsSoftware\ExaPHP\Firewall\Framework\Http\Middleware\FirewallMiddleware::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Firewall\Framework\Http\Middleware\FirewallMiddleware::process()
     */
    public function testForbidden(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $firewall = $this->createMock(FirewallInterface::class);
        $firewall
            ->expects($this->once())
            ->method('isAllowed')
            ->with($request)
            ->willReturn(false);

        $chain = $this->createMock(MiddlewareChainInterface::class);

        /**
         * @var FirewallInterface        $firewall
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new FirewallMiddleware($firewall);
        $response = $middleware->process($request, $chain);

        $this->assertInstanceOf(ForbiddenProblemDetails::class, $response->getBody());
    }
}
