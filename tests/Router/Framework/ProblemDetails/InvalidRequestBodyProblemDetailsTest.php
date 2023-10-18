<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Framework\ProblemDetails;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Request\Uri\UriInterface;
use ExtendsSoftware\ExaPHP\Router\Exception\InvalidRequestBody;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use PHPUnit\Framework\TestCase;

class InvalidRequestBodyProblemDetailsTest extends TestCase
{
    /**
     * Test that getters will return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Framework\ProblemDetails\InvalidRequestBodyProblemDetails::__construct()
     */
    public function testGetters(): void
    {
        $result = $this->createMock(ResultInterface::class);

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

        $exception = $this->createMock(InvalidRequestBody::class);
        $exception
            ->expects($this->once())
            ->method('getResult')
            ->willReturn($result);

        /**
         * @var RequestInterface $request
         * @var InvalidRequestBody $exception
         */
        $problemDetails = new InvalidRequestBodyProblemDetails($request, $exception);

        $this->assertSame('/problems/router/invalid-request-body', $problemDetails->getType());
        $this->assertSame('Invalid request body', $problemDetails->getTitle());
        $this->assertSame('Request body is invalid.', $problemDetails->getDetail());
        $this->assertSame(400, $problemDetails->getStatus());
        $this->assertSame('/foo/bar', $problemDetails->getInstance());
        $this->assertSame(['result' => $result], $problemDetails->getAdditional());
    }
}
