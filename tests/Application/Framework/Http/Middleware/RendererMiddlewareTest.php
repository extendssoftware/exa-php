<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application\Framework\Http\Middleware;

use ExtendsSoftware\ExaPHP\Application\Http\Renderer\RendererInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use PHPUnit\Framework\TestCase;

class RendererMiddlewareTest extends TestCase
{
    /**
     * Process.
     *
     * Test that renderer will be called with response from next in chain.
     *
     * @covers \ExtendsSoftware\ExaPHP\Application\Framework\Http\Middleware\RendererMiddleware::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Application\Framework\Http\Middleware\RendererMiddleware::process()
     */
    public function testProcess(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $response = $this->createMock(ResponseInterface::class);

        $chain = $this->createMock(MiddlewareChainInterface::class);
        $chain
            ->expects($this->once())
            ->method('proceed')
            ->with($request)
            ->willReturn($response);

        $renderer = $this->createMock(RendererInterface::class);
        $renderer
            ->expects($this->once())
            ->method('render')
            ->with($response);

        /**
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         * @var RendererInterface        $renderer
         */
        $middleware = new RendererMiddleware($renderer);
        $response = $middleware->process($request, $chain);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
