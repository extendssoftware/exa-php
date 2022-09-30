<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Framework\ProblemDetails;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Request\Uri\UriInterface;
use ExtendsSoftware\ExaPHP\Router\Route\Method\Exception\MethodNotAllowed;
use ExtendsSoftware\ExaPHP\Router\Route\Query\Exception\QueryParameterMissing;
use PHPUnit\Framework\TestCase;

class QueryParameterMissingProblemDetailsTest extends TestCase
{
    /**
     * Test that getters will return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Framework\ProblemDetails\QueryParameterMissingProblemDetails::__construct()
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

        $exception = $this->createMock(QueryParameterMissing::class);
        $exception
            ->expects($this->exactly(2))
            ->method('getParameter')
            ->willReturn('author');

        /**
         * @var RequestInterface $request
         * @var MethodNotAllowed $exception
         */
        $problemDetails = new QueryParameterMissingProblemDetails($request, $exception);

        $this->assertSame('/problems/router/query-parameter-missing', $problemDetails->getType());
        $this->assertSame('Query parameter missing', $problemDetails->getTitle());
        $this->assertSame('Query parameter "author" is missing.', $problemDetails->getDetail());
        $this->assertSame(400, $problemDetails->getStatus());
        $this->assertSame('/foo/bar', $problemDetails->getInstance());
        $this->assertSame(['parameter' => 'author'], $problemDetails->getAdditional());
    }
}
