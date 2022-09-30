<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Framework\ProblemDetails;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Request\Uri\UriInterface;
use PHPUnit\Framework\TestCase;

class NotFoundProblemDetailsTest extends TestCase
{
    /**
     * Test that getters will return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Framework\ProblemDetails\NotFoundProblemDetails::__construct()
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

        /**
         * @var RequestInterface $request
         */
        $problemDetails = new NotFoundProblemDetails($request);

        $this->assertSame('/problems/router/not-found', $problemDetails->getType());
        $this->assertSame('Not found', $problemDetails->getTitle());
        $this->assertSame('Request could not be matched by a route.', $problemDetails->getDetail());
        $this->assertSame(404, $problemDetails->getStatus());
        $this->assertSame('/foo/bar', $problemDetails->getInstance());
    }
}
