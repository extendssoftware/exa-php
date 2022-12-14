<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Framework\Http\Middleware\Controller;

use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Router\Controller\Executor\ExecutorInterface;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatchInterface;
use PHPUnit\Framework\TestCase;

class ControllerMiddlewareTest extends TestCase
{
    /**
     * Process.
     *
     * Test that executor will be called with request and route match.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Framework\Http\Middleware\Controller\ControllerMiddleware::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Framework\Http\Middleware\Controller\ControllerMiddleware::process()
     */
    public function testProcess(): void
    {
        $chain = $this->createMock(MiddlewareChainInterface::class);

        $match = $this->createMock(RouteMatchInterface::class);
        $match
            ->expects($this->once())
            ->method('getParameter')
            ->with('controller')
            ->willReturn('foo');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('routeMatch')
            ->willReturn($match);

        $response = $this->createMock(ResponseInterface::class);

        $executor = $this->createMock(ExecutorInterface::class);
        $executor
            ->expects($this->once())
            ->method('execute')
            ->with($request, $match)
            ->willReturn($response);

        /**
         * @var ExecutorInterface $executor
         * @var RequestInterface $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new ControllerMiddleware($executor);

        $this->assertSame($response, $middleware->process($request, $chain));
    }

    /**
     * Non route match.
     *
     * Test that middleware chain will be called when there is no route match.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Framework\Http\Middleware\Controller\ControllerMiddleware::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Framework\Http\Middleware\Controller\ControllerMiddleware::process()
     */
    public function testNonRouteMatch(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('routeMatch')
            ->willReturn(null);

        $response = $this->createMock(ResponseInterface::class);

        $chain = $this->createMock(MiddlewareChainInterface::class);
        $chain
            ->expects($this->once())
            ->method('proceed')
            ->with($request)
            ->willReturn($response);

        $executor = $this->createMock(ExecutorInterface::class);

        /**
         * @var ExecutorInterface $executor
         * @var RequestInterface $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new ControllerMiddleware($executor);

        $this->assertSame($response, $middleware->process($request, $chain));
    }

    /**
     * Controller parameter missing.
     *
     * Test that middleware chain will be called when controller parameter is missing on route match.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Framework\Http\Middleware\Controller\ControllerMiddleware::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Framework\Http\Middleware\Controller\ControllerMiddleware::process()
     */
    public function testControllerParameterMissing(): void
    {
        $match = $this->createMock(RouteMatchInterface::class);
        $match
            ->expects($this->once())
            ->method('getParameter')
            ->with('controller')
            ->willReturn(null);

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('routeMatch')
            ->willReturn($match);

        $response = $this->createMock(ResponseInterface::class);

        $chain = $this->createMock(MiddlewareChainInterface::class);
        $chain
            ->expects($this->once())
            ->method('proceed')
            ->with($request)
            ->willReturn($response);

        $executor = $this->createMock(ExecutorInterface::class);

        /**
         * @var ExecutorInterface $executor
         * @var RequestInterface $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new ControllerMiddleware($executor);

        $this->assertSame($response, $middleware->process($request, $chain));
    }
}
