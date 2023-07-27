<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Framework\Http\Middleware\Logger;

use Exception;
use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Logger\LoggerInterface;
use ExtendsSoftware\ExaPHP\Logger\Priority\Error\ErrorPriority;
use PHPUnit\Framework\TestCase;
use Throwable;

class LoggerMiddlewareTest extends TestCase
{
    /**
     * Process.
     *
     * Test that response from chain will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Logger\Framework\Http\Middleware\Logger\LoggerMiddleware::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Framework\Http\Middleware\Logger\LoggerMiddleware::process()
     */
    public function testProcess(): void
    {
        $logger = $this->createMock(LoggerInterface::class);

        $request = $this->createMock(RequestInterface::class);

        $chain = $this->createMock(MiddlewareChainInterface::class);
        $chain
            ->expects($this->once())
            ->method('proceed')
            ->with($request)
            ->willReturn($this->createMock(ResponseInterface::class));

        /**
         * @var LoggerInterface $logger
         * @var MiddlewareChainInterface $chain
         * @var RequestInterface $request
         */
        $middleware = new LoggerMiddleware($logger);
        $response = $middleware->process($request, $chain);

        $this->assertIsObject($response);
    }

    /**
     * Log.
     *
     * Test that exception will be caught and message will be logged.
     *
     * @covers \ExtendsSoftware\ExaPHP\Logger\Framework\Http\Middleware\Logger\LoggerMiddleware::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Framework\Http\Middleware\Logger\LoggerMiddleware::process()
     */
    public function testLog(): void
    {
        $throwable = new Exception('Fancy exception message!', 123);

        $logger = $this->createMock(LoggerInterface::class);
        $logger
            ->expects($this->once())
            ->method('log')
            ->with(
                'Fancy exception message!',
                $this->isInstanceOf(ErrorPriority::class)
            );

        $request = $this->createMock(RequestInterface::class);

        $chain = $this->createMock(MiddlewareChainInterface::class);
        $chain
            ->expects($this->once())
            ->method('proceed')
            ->with($request)
            ->willThrowException($throwable);

        /**
         * @var LoggerInterface $logger
         * @var MiddlewareChainInterface $chain
         * @var RequestInterface $request
         */
        $middleware = new LoggerMiddleware($logger);

        try {
            $middleware->process($request, $chain);
        } catch (Throwable $exception) {
            $this->assertSame($throwable, $exception);
        }
    }
}
