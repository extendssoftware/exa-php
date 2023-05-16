<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Expander;

use ExtendsSoftware\ExaPHP\Hateoas\Builder\BuilderInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Link\LinkInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Request\Uri\UriInterface;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Router\Executor\ExecutorInterface;
use ExtendsSoftware\ExaPHP\Router\Route\Match\RouteMatchInterface;
use ExtendsSoftware\ExaPHP\Router\RouterInterface;
use PHPUnit\Framework\TestCase;

class ExpanderTest extends TestCase
{
    /**
     * Expand.
     *
     * Test that link will be expanded will builder returned from controller.
     *
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Expander\Expander::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Expander\Expander::expand()
     */
    public function testExpand(): void
    {
        $builder = $this->createMock(BuilderInterface::class);

        $uri = $this->createMock(UriInterface::class);

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('withUri')
            ->with($uri)
            ->willReturnSelf();

        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($builder);

        $match = $this->createMock(RouteMatchInterface::class);

        $router = $this->createMock(RouterInterface::class);
        $router
            ->expects($this->once())
            ->method('route')
            ->with($request)
            ->willReturn($match);

        $executor = $this->createMock(ExecutorInterface::class);
        $executor
            ->expects($this->once())
            ->method('execute')
            ->with($request, $match)
            ->willReturn($response);

        $link = $this->createMock(LinkInterface::class);
        $link
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        /**
         * @var RouterInterface   $router
         * @var ExecutorInterface $executor
         * @var LinkInterface     $link
         */
        $expander = new Expander($router, $executor);

        $this->assertSame($builder, $expander->expand($link, $request));
    }
}
