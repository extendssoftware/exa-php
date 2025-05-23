<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Framework\ProblemDetails;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Request\Uri\UriInterface;
use ExtendsSoftware\ExaPHP\Router\Exception\InvalidQueryString;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use PHPUnit\Framework\TestCase;

class InvalidQueryStringProblemDetailsTest extends TestCase
{
    /**
     * Test that getters will return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Framework\ProblemDetails\InvalidQueryStringProblemDetails::__construct()
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

        $result = $this->createMock(ResultInterface::class);

        $exception = $this->createMock(InvalidQueryString::class);
        $exception
            ->expects($this->once())
            ->method('getParameter')
            ->willReturn('limit');

        $exception
            ->expects($this->once())
            ->method('getResult')
            ->willReturn($result);

        /**
         * @var RequestInterface $request
         * @var InvalidQueryString $exception
         */
        $problemDetails = new InvalidQueryStringProblemDetails($request, $exception);

        $this->assertSame('/problems/router/invalid-query-string', $problemDetails->getType());
        $this->assertSame('Invalid query string', $problemDetails->getTitle());
        $this->assertSame('Value for query string parameter is invalid.', $problemDetails->getDetail());
        $this->assertSame(400, $problemDetails->getStatus());
        $this->assertSame('/foo/bar', $problemDetails->getInstance());
        $this->assertSame(['parameter' => 'limit', 'reason' => $result], $problemDetails->getAdditional());
    }
}
