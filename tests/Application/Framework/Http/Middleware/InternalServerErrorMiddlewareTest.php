<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application\Framework\Http\Middleware;

use Exception;
use ExtendsSoftware\ExaPHP\Application\Framework\ProblemDetails\InternalServerErrorProblemDetails;
use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use PHPUnit\Framework\TestCase;

class InternalServerErrorMiddlewareTest extends TestCase
{
    /**
     * Process.
     *
     * Test that chain is called with request and response will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Application\Framework\Http\Middleware\InternalServerErrorMiddleware::process()
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

        /**
         * @var RequestInterface $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new InternalServerErrorMiddleware();

        $this->assertSame($response, $middleware->process($request, $chain));
    }

    /**
     * Exception.
     *
     * Test that exception will be caught and a new response will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Application\Framework\Http\Middleware\InternalServerErrorMiddleware::process()
     */
    public function testException(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $chain = $this->createMock(MiddlewareChainInterface::class);
        $chain
            ->expects($this->once())
            ->method('proceed')
            ->with($request)
            ->willThrowException(new Exception('Fancy exception message!', 136));

        /**
         * @var RequestInterface $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new InternalServerErrorMiddleware();
        $response = $middleware->process($request, $chain);

        $problemDetails = $response->getBody();
        $this->assertInstanceOf(InternalServerErrorProblemDetails::class, $problemDetails);
        $this->assertNull($problemDetails->getAdditional());
    }
}
