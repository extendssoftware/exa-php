<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Framework\Http\Middleware\Hateoas;

use ExtendsSoftware\ExaPHP\Authorization\AuthorizerInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Builder\BuilderInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Builder\BuilderProviderInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Builder\Exception\AttributeNotFound;
use ExtendsSoftware\ExaPHP\Hateoas\Builder\Exception\LinkNotEmbeddable;
use ExtendsSoftware\ExaPHP\Hateoas\Builder\Exception\LinkNotFound;
use ExtendsSoftware\ExaPHP\Hateoas\Expander\ExpanderInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Framework\ProblemDetails\AttributeNotFoundProblemDetails;
use ExtendsSoftware\ExaPHP\Hateoas\Framework\ProblemDetails\LinkNotEmbeddableProblemDetails;
use ExtendsSoftware\ExaPHP\Hateoas\Framework\ProblemDetails\LinkNotFoundProblemDetails;
use ExtendsSoftware\ExaPHP\Hateoas\ResourceInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Serializer\SerializerInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Request\Uri\UriInterface;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;
use ExtendsSoftware\ExaPHP\Router\RouterInterface;
use PHPUnit\Framework\TestCase;

class HateoasMiddlewareTest extends TestCase
{
    /**
     * Test that middleware gets response from middleware chain and serializes resource from body.
     *
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Framework\Http\Middleware\Hateoas\HateoasMiddleware::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Framework\Http\Middleware\Hateoas\HateoasMiddleware::process()
     */
    public function testProcess(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getQuery')
            ->willReturn([
                'foo' => 'bar',
                'expand' => [
                    'first',
                ],
                'project' => [
                    'second',
                ],
            ]);

        $uri
            ->expects($this->once())
            ->method('withQuery')
            ->with([
                'foo' => 'bar',
            ])
            ->willReturnSelf();

        $resource = $this->createMock(ResourceInterface::class);

        $identity = $this->createMock(IdentityInterface::class);

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        $request
            ->expects($this->once())
            ->method('withUri')
            ->with($uri)
            ->willReturnSelf();

        $request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('identity')
            ->willReturn($identity);

        $authorizer = $this->createMock(AuthorizerInterface::class);

        $expander = $this->createMock(ExpanderInterface::class);

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer
            ->expects($this->once())
            ->method('serialize')
            ->with($resource)
            ->willReturn('{"name":"Jáne Doe"}');

        $builder = $this->createMock(BuilderInterface::class);
        $builder
            ->expects($this->once())
            ->method('setAuthorizer')
            ->with($authorizer)
            ->willReturnSelf();

        $builder
            ->expects($this->once())
            ->method('setExpander')
            ->with($expander)
            ->willReturnSelf();

        $builder
            ->expects($this->once())
            ->method('setIdentity')
            ->with($identity)
            ->willReturnSelf();

        $builder
            ->expects($this->once())
            ->method('setToExpand')
            ->with([
                'first',
            ])
            ->willReturnSelf();

        $builder
            ->expects($this->once())
            ->method('setToProject')
            ->with([
                'second',
            ])
            ->willReturnSelf();

        $builder
            ->expects($this->once())
            ->method('build')
            ->willReturn($resource);

        $provider = $this->createMock(BuilderProviderInterface::class);
        $provider
            ->expects($this->once())
            ->method('getBuilder')
            ->willReturn($builder);

        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($provider);

        $response
            ->expects($this->exactly(2))
            ->method('withHeader')
            ->willReturnCallback(fn($header, $value) => match ([$header, $value]) {
                ['Content-Type', 'application/hal+json'],
                ['Content-Length', '19'] => $response,
            });

        $response
            ->expects($this->once())
            ->method('withBody')
            ->with('{"name":"Jáne Doe"}')
            ->willReturnSelf();

        $chain = $this->createMock(MiddlewareChainInterface::class);
        $chain
            ->expects($this->once())
            ->method('proceed')
            ->with($request)
            ->willReturn($response);

        $router = $this->createMock(RouterInterface::class);

        /**
         * @var AuthorizerInterface      $authorizer
         * @var ExpanderInterface        $expander
         * @var SerializerInterface      $serializer
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         * @var ResponseInterface        $response
         * @var RouterInterface          $router
         */
        $middleware = new HateoasMiddleware($authorizer, $expander, $serializer, $router);

        $this->assertSame($response, $middleware->process($request, $chain));
    }

    /**
     * Test that correct response will be returned on LinkNotFound exception.
     *
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Framework\Http\Middleware\Hateoas\HateoasMiddleware::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Framework\Http\Middleware\Hateoas\HateoasMiddleware::process()
     */
    public function testLinkNotFoundResponse(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getQuery')
            ->willReturn([
                'foo' => 'bar',
                'expand' => [
                    'first',
                ],
                'project' => [
                    'second',
                ],
            ]);

        $uri
            ->expects($this->once())
            ->method('withQuery')
            ->with([
                'foo' => 'bar',
            ])
            ->willReturnSelf();

        $identity = $this->createMock(IdentityInterface::class);

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->exactly(2))
            ->method('getUri')
            ->willReturn($uri);

        $request
            ->expects($this->once())
            ->method('withUri')
            ->with($uri)
            ->willReturnSelf();

        $request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('identity')
            ->willReturn($identity);

        $authorizer = $this->createMock(AuthorizerInterface::class);

        $expander = $this->createMock(ExpanderInterface::class);

        $serializer = $this->createMock(SerializerInterface::class);

        $builder = $this->createMock(BuilderInterface::class);
        $builder
            ->expects($this->once())
            ->method('setAuthorizer')
            ->with($authorizer)
            ->willReturnSelf();

        $builder
            ->expects($this->once())
            ->method('setExpander')
            ->with($expander)
            ->willReturnSelf();

        $builder
            ->expects($this->once())
            ->method('setIdentity')
            ->with($identity)
            ->willReturnSelf();

        $builder
            ->expects($this->once())
            ->method('setToExpand')
            ->with([
                'first',
            ])
            ->willReturnSelf();

        $builder
            ->expects($this->once())
            ->method('setToProject')
            ->with([
                'second',
            ])
            ->willReturnSelf();

        $builder
            ->expects($this->once())
            ->method('build')
            ->willThrowException(new LinkNotFound('author'));

        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($builder);

        $chain = $this->createMock(MiddlewareChainInterface::class);
        $chain
            ->expects($this->once())
            ->method('proceed')
            ->with($request)
            ->willReturn($response);

        $router = $this->createMock(RouterInterface::class);

        /**
         * @var AuthorizerInterface      $authorizer
         * @var ExpanderInterface        $expander
         * @var SerializerInterface      $serializer
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         * @var RouterInterface          $router
         */
        $middleware = new HateoasMiddleware($authorizer, $expander, $serializer, $router);

        $response = $middleware->process($request, $chain);
        $this->assertInstanceOf(LinkNotFoundProblemDetails::class, $response->getBody());
    }

    /**
     * Test that correct response will be returned on LinkNotEmbeddable exception.
     *
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Framework\Http\Middleware\Hateoas\HateoasMiddleware::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Framework\Http\Middleware\Hateoas\HateoasMiddleware::process()
     */
    public function testLinkNotEmbeddableResponse(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getQuery')
            ->willReturn([
                'foo' => 'bar',
                'expand' => [
                    'first',
                ],
                'project' => [
                    'second',
                ],
            ]);

        $uri
            ->expects($this->once())
            ->method('withQuery')
            ->with([
                'foo' => 'bar',
            ])
            ->willReturnSelf();

        $identity = $this->createMock(IdentityInterface::class);

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->exactly(2))
            ->method('getUri')
            ->willReturn($uri);

        $request
            ->expects($this->once())
            ->method('withUri')
            ->with($uri)
            ->willReturnSelf();

        $request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('identity')
            ->willReturn($identity);

        $authorizer = $this->createMock(AuthorizerInterface::class);

        $expander = $this->createMock(ExpanderInterface::class);

        $serializer = $this->createMock(SerializerInterface::class);

        $builder = $this->createMock(BuilderInterface::class);
        $builder
            ->expects($this->once())
            ->method('setAuthorizer')
            ->with($authorizer)
            ->willReturnSelf();

        $builder
            ->expects($this->once())
            ->method('setExpander')
            ->with($expander)
            ->willReturnSelf();

        $builder
            ->expects($this->once())
            ->method('setIdentity')
            ->with($identity)
            ->willReturnSelf();

        $builder
            ->expects($this->once())
            ->method('setToExpand')
            ->with([
                'first',
            ])
            ->willReturnSelf();

        $builder
            ->expects($this->once())
            ->method('setToProject')
            ->with([
                'second',
            ])
            ->willReturnSelf();

        $builder
            ->expects($this->once())
            ->method('build')
            ->willThrowException(new LinkNotEmbeddable('comments'));

        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($builder);

        $chain = $this->createMock(MiddlewareChainInterface::class);
        $chain
            ->expects($this->once())
            ->method('proceed')
            ->with($request)
            ->willReturn($response);

        $router = $this->createMock(RouterInterface::class);

        /**
         * @var AuthorizerInterface      $authorizer
         * @var ExpanderInterface        $expander
         * @var SerializerInterface      $serializer
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         * @var RouterInterface          $router
         */
        $middleware = new HateoasMiddleware($authorizer, $expander, $serializer, $router);

        $response = $middleware->process($request, $chain);
        $this->assertInstanceOf(LinkNotEmbeddableProblemDetails::class, $response->getBody());
    }

    /**
     * Test that correct response will be returned on AttributeNotFound exception.
     *
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Framework\Http\Middleware\Hateoas\HateoasMiddleware::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Framework\Http\Middleware\Hateoas\HateoasMiddleware::process()
     */
    public function testAttributeNotFoundResponse(): void
    {
        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('getQuery')
            ->willReturn([
                'foo' => 'bar',
                'expand' => [
                    'first',
                ],
                'project' => [
                    'second',
                ],
            ]);

        $uri
            ->expects($this->once())
            ->method('withQuery')
            ->with([
                'foo' => 'bar',
            ])
            ->willReturnSelf();

        $identity = $this->createMock(IdentityInterface::class);

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->exactly(2))
            ->method('getUri')
            ->willReturn($uri);

        $request
            ->expects($this->once())
            ->method('withUri')
            ->with($uri)
            ->willReturnSelf();

        $request
            ->expects($this->once())
            ->method('getAttribute')
            ->with('identity')
            ->willReturn($identity);

        $authorizer = $this->createMock(AuthorizerInterface::class);

        $expander = $this->createMock(ExpanderInterface::class);

        $serializer = $this->createMock(SerializerInterface::class);

        $builder = $this->createMock(BuilderInterface::class);
        $builder
            ->expects($this->once())
            ->method('setAuthorizer')
            ->with($authorizer)
            ->willReturnSelf();

        $builder
            ->expects($this->once())
            ->method('setExpander')
            ->with($expander)
            ->willReturnSelf();

        $builder
            ->expects($this->once())
            ->method('setIdentity')
            ->with($identity)
            ->willReturnSelf();

        $builder
            ->expects($this->once())
            ->method('setToExpand')
            ->with([
                'first',
            ])
            ->willReturnSelf();

        $builder
            ->expects($this->once())
            ->method('setToProject')
            ->with([
                'second',
            ])
            ->willReturnSelf();

        $builder
            ->expects($this->once())
            ->method('build')
            ->willThrowException(new AttributeNotFound('name'));

        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($builder);

        $chain = $this->createMock(MiddlewareChainInterface::class);
        $chain
            ->expects($this->once())
            ->method('proceed')
            ->with($request)
            ->willReturn($response);

        $router = $this->createMock(RouterInterface::class);

        /**
         * @var AuthorizerInterface      $authorizer
         * @var ExpanderInterface        $expander
         * @var SerializerInterface      $serializer
         * @var RequestInterface         $request
         * @var MiddlewareChainInterface $chain
         * @var RouterInterface          $router
         */
        $middleware = new HateoasMiddleware($authorizer, $expander, $serializer, $router);

        $response = $middleware->process($request, $chain);
        $this->assertInstanceOf(AttributeNotFoundProblemDetails::class, $response->getBody());
    }
}
