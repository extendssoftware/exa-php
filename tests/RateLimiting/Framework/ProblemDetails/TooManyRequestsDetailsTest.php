<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\RateLimiting\Framework\ProblemDetails;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Request\Uri\UriInterface;
use ExtendsSoftware\ExaPHP\RateLimiting\Quota\QuotaInterface;
use PHPUnit\Framework\TestCase;

class TooManyRequestsDetailsTest extends TestCase
{
    /**
     * Test that getters will return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\RateLimiting\Framework\ProblemDetails\TooManyRequestsProblemDetails::__construct()
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

        $quota = $this->createMock(QuotaInterface::class);
        $quota
            ->expects($this->once())
            ->method('getLimit')
            ->willReturn(10);

        $quota
            ->expects($this->once())
            ->method('getRemaining')
            ->willReturn(5);

        $quota
            ->expects($this->once())
            ->method('getReset')
            ->willReturn(1666697250);

        /**
         * @var RequestInterface $request
         * @var QuotaInterface   $quota
         */
        $problemDetails = new TooManyRequestsProblemDetails($request, $quota);

        $this->assertSame('/problems/rate-limiting/too-many-requests', $problemDetails->getType());
        $this->assertSame('Too Many Requests', $problemDetails->getTitle());
        $this->assertSame('Rate limit exceeded.', $problemDetails->getDetail());
        $this->assertSame(429, $problemDetails->getStatus());
        $this->assertSame('/foo/bar', $problemDetails->getInstance());
        $this->assertSame([
            'X-RateLimit-Limit' => 10,
            'X-RateLimit-Remaining' => 5,
            'X-RateLimit-Reset' => 1666697250,
        ], $problemDetails->getAdditional());
    }
}
