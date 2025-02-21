<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Framework\ProblemDetails;

use ExtendsSoftware\ExaPHP\Hateoas\Builder\Exception\LinkNotFound;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Request\Uri\UriInterface;
use PHPUnit\Framework\TestCase;

class LinkNotFoundProblemDetailsTest extends TestCase
{
    /**
     * Test that getters will return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Framework\ProblemDetails\LinkNotFoundProblemDetails::__construct()
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

        $exception = $this->createMock(LinkNotFound::class);
        $exception
            ->expects($this->once())
            ->method('getRel')
            ->willReturn('author');

        /**
         * @var RequestInterface $request
         * @var LinkNotFound $exception
         */
        $problemDetails = new LinkNotFoundProblemDetails($request, $exception);

        $this->assertSame('/problems/hateoas/link-not-found', $problemDetails->getType());
        $this->assertSame('Link not found', $problemDetails->getTitle());
        $this->assertSame('Link with rel can not be found.', $problemDetails->getDetail());
        $this->assertSame(404, $problemDetails->getStatus());
        $this->assertSame('/foo/bar', $problemDetails->getInstance());
        $this->assertSame(['rel' => 'author'], $problemDetails->getAdditional());
    }
}
