<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Framework\ProblemDetails;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Request\Uri\UriInterface;
use ExtendsSoftware\ExaPHP\Router\Exception\QueryParametersNotAllowed;
use PHPUnit\Framework\TestCase;

class QueryParameterNotAllowedProblemDetailsTest extends TestCase
{
    /**
     * Test that getters will return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Framework\ProblemDetails\QueryParameterNotAllowedProblemDetails::__construct()
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

        $exception = $this->createMock(QueryParametersNotAllowed::class);
        $exception
            ->expects($this->exactly(2))
            ->method('getParameters')
            ->willReturn(['author']);

        /**
         * @var RequestInterface          $request
         * @var QueryParametersNotAllowed $exception
         */
        $problemDetails = new QueryParameterNotAllowedProblemDetails($request, $exception);

        $this->assertSame('/problems/router/query-parameter-not-allowed', $problemDetails->getType());
        $this->assertSame('Query parameter not allowed', $problemDetails->getTitle());
        $this->assertSame('Query string parameters "author" are not allowed.', $problemDetails->getDetail());
        $this->assertSame(400, $problemDetails->getStatus());
        $this->assertSame('/foo/bar', $problemDetails->getInstance());
        $this->assertSame(['parameters' => ['author']], $problemDetails->getAdditional());
    }
}
