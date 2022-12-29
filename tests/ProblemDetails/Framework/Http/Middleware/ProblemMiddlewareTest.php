<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ProblemDetails\Framework\Http\Middleware;

use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\ProblemDetails\ProblemDetailsInterface;
use ExtendsSoftware\ExaPHP\ProblemDetails\Serializer\SerializerInterface;
use PHPUnit\Framework\TestCase;

class ProblemMiddlewareTest extends TestCase
{
    /**
     * Process.
     *
     * Test that middleware will serialize problem response body.
     *
     * @covers \ExtendsSoftware\ExaPHP\ProblemDetails\Framework\Http\Middleware\ProblemDetailsMiddleware::__construct()
     * @covers \ExtendsSoftware\ExaPHP\ProblemDetails\Framework\Http\Middleware\ProblemDetailsMiddleware::process
     */
    public function testProcess(): void
    {
        $problem = $this->createMock(ProblemDetailsInterface::class);
        $problem
            ->expects($this->once())
            ->method('getStatus')
            ->willReturn(400);

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer
            ->expects($this->once())
            ->method('serialize')
            ->with($problem)
            ->willReturn('{"type":"/foo/bár}');

        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($problem);

        $response
            ->expects($this->exactly(2))
            ->method('withHeader')
            ->withConsecutive(
                ['Content-Type', 'application/problem+json'],
                ['Content-Length', '18']
            )
            ->willReturnSelf();

        $response
            ->expects($this->once())
            ->method('withBody')
            ->with('{"type":"/foo/bár}')
            ->willReturnSelf();

        $response
            ->expects($this->once())
            ->method('withStatusCode')
            ->with(400)
            ->willReturnSelf();

        $request = $this->createMock(RequestInterface::class);

        $chain = $this->createMock(MiddlewareChainInterface::class);
        $chain
            ->expects($this->once())
            ->method('proceed')
            ->with($request)
            ->willReturn($response);

        /**
         * @var SerializerInterface $serializer
         * @var RequestInterface $request
         * @var MiddlewareChainInterface $chain
         */
        $middleware = new ProblemDetailsMiddleware($serializer);

        $this->assertSame($response, $middleware->process($request, $chain));
    }
}
