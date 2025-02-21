<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authorization\Framework\ProblemDetails;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Request\Uri\UriInterface;
use PHPUnit\Framework\TestCase;

class ForbiddenProblemDetailsTest extends TestCase
{
    /**
     * Test that getters will return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Authorization\Framework\ProblemDetails\ForbiddenProblemDetails::__construct()
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
        $problemDetails = new ForbiddenProblemDetails($request);

        $this->assertSame('/problems/authorization/forbidden', $problemDetails->getType());
        $this->assertSame('Forbidden', $problemDetails->getTitle());
        $this->assertSame('Failed to authorize request.', $problemDetails->getDetail());
        $this->assertSame(403, $problemDetails->getStatus());
        $this->assertSame('/foo/bar', $problemDetails->getInstance());
        $this->assertNull($problemDetails->getAdditional());
    }
}
