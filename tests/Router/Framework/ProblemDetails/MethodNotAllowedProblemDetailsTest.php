<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Framework\ProblemDetails;

use ExtendsSoftware\ExaPHP\Http\Request\Method\Method;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Request\Uri\UriInterface;
use ExtendsSoftware\ExaPHP\Router\Exception\MethodNotAllowed;
use PHPUnit\Framework\TestCase;

class MethodNotAllowedProblemDetailsTest extends TestCase
{
    /**
     * Test that getters will return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Framework\ProblemDetails\MethodNotAllowedProblemDetails::__construct()
     */
    public function testGetters(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('toRelative')
            ->willReturn('/foo/bar');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        $exception = $this->createMock(MethodNotAllowed::class);
        $exception
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn(Method::GET);

        $exception
            ->expects($this->once())
            ->method('getAllowedMethods')
            ->willReturn([Method::PUT, Method::POST]);

        /**
         * @var RequestInterface $request
         * @var MethodNotAllowed $exception
         */
        $problemDetails = new MethodNotAllowedProblemDetails($request, $exception);

        $this->assertSame('/problems/router/method-not-allowed', $problemDetails->getType());
        $this->assertSame('Method not allowed', $problemDetails->getTitle());
        $this->assertSame('Method is not allowed.', $problemDetails->getDetail());
        $this->assertSame(405, $problemDetails->getStatus());
        $this->assertSame('/foo/bar', $problemDetails->getInstance());
        $this->assertSame(['method' => 'GET', 'allowed_methods' => ['PUT', 'POST']], $problemDetails->getAdditional());
    }
}
