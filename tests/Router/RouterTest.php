<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router;

use ExtendsSoftware\ExaPHP\Http\Request\Method\Method;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Request\Uri\UriInterface;
use ExtendsSoftware\ExaPHP\Router\Exception\InvalidQueryString;
use ExtendsSoftware\ExaPHP\Router\Exception\InvalidRequestBody;
use ExtendsSoftware\ExaPHP\Router\Exception\MethodNotAllowed;
use ExtendsSoftware\ExaPHP\Router\Exception\NotFound;
use ExtendsSoftware\ExaPHP\Router\Exception\PathParameterMissing;
use ExtendsSoftware\ExaPHP\Router\Exception\QueryParametersNotAllowed;
use ExtendsSoftware\ExaPHP\Router\Exception\RouteNotFound;
use ExtendsSoftware\ExaPHP\Router\Route\Definition\RouteDefinitionInterface;
use ExtendsSoftware\ExaPHP\Router\Route\Match\RouteMatchInterface;
use ExtendsSoftware\ExaPHP\Router\Route\RouteInterface;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class RouterTest extends TestCase
{
    /**
     * Route.
     *
     * Test that router can match route and return RouteMatchInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::addDefinition()
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::route()
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::parseUrl()
     *                                                          @covers \ExtendsSoftware\ExaPHP\Router\Router::parseStringInteger()
     */
    public function testRoute(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->expects($this->exactly(2))
            ->method('isValid')
            ->willReturn(true);

        $validator = $this->createMock(ValidatorInterface::class);
        $validator
            ->expects($this->exactly(2))
            ->method('validate')
            ->willReturn($result);

        $route = $this->createMock(RouteInterface::class);
        $route
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('/blogs/:blogId/comments?limit=10&page=1&filled[]&empty[]&default[]=a');

        $route
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn(Method::GET);

        $route->expects($this->once())
            ->method('getParameters')
            ->willReturn(['permission' => 'route/blogs/blog/comments']);

        $route
            ->expects($this->once())
            ->method('getValidators')
            ->willReturn([
                'blogId' => $validator,
                'limit' => $validator,
                'page' => $validator,
                'multiple' => $validator,
            ]);

        $definition = $this->createMock(RouteDefinitionInterface::class);
        $definition
            ->expects($this->once())
            ->method('getRoute')
            ->willReturn($route);

        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('toRelative')
            ->willReturn('/blogs/1234/comments?page=2&filled[]=&filled[]=0&filled[]=1');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        $request
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn(Method::GET);

        /**
         * @var RouteDefinitionInterface $definition
         * @var RequestInterface         $request
         */
        $router = new Router();
        $routeMatch = $router
            ->addDefinition($definition)
            ->route($request);

        $this->assertInstanceOf(RouteMatchInterface::class, $routeMatch);
        $this->assertSame([
            'permission' => 'route/blogs/blog/comments',
            'blogId' => 1234,
            'limit' => 10,
            'page' => 2,
            'filled' => [0, 1],
            'default' => ['a'],
        ], $routeMatch->getParameters());
        $this->assertSame($definition, $routeMatch->getDefinition());
    }

    /**
     * Path count mismatch.
     *
     * Test that route will not match on path count mismatch.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::addDefinition()
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::route()
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::parseUrl()
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::parseStringInteger()
     * @covers \ExtendsSoftware\ExaPHP\Router\Exception\NotFound::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Exception\NotFound::getRequest()
     */
    public function testPathCountMismatch(): void
    {
        $route = $this->createMock(RouteInterface::class);
        $route
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('/');

        $definition = $this->createMock(RouteDefinitionInterface::class);
        $definition
            ->expects($this->once())
            ->method('getRoute')
            ->willReturn($route);

        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('toRelative')
            ->willReturn('/blogs/1234');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        try {
            /**
             * @var RouteDefinitionInterface $definition
             * @var RequestInterface         $request
             */
            $router = new Router();
            $router
                ->addDefinition($definition)
                ->route($request);
        } catch (NotFound $exception) {
            $this->assertSame('Request could not be matched by a route.', $exception->getMessage());
            $this->assertSame($request, $exception->getRequest());
        }
    }

    /**
     * Invalid path part.
     *
     * Test that route will not match on invalid path part.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::addDefinition()
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::route()
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::parseUrl()
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::parseStringInteger()
     * @covers \ExtendsSoftware\ExaPHP\Router\Exception\NotFound::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Exception\NotFound::getRequest()
     */
    public function testInvalidPathPartValue(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(false);

        $validator = $this->createMock(ValidatorInterface::class);
        $validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn($result);

        $route = $this->createMock(RouteInterface::class);
        $route
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('/blogs/:blogId');

        $route->expects($this->once())
            ->method('getParameters')
            ->willReturn([]);

        $route
            ->expects($this->once())
            ->method('getValidators')
            ->willReturn([
                'blogId' => $validator,
            ]);

        $definition = $this->createMock(RouteDefinitionInterface::class);
        $definition
            ->expects($this->once())
            ->method('getRoute')
            ->willReturn($route);

        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('toRelative')
            ->willReturn('/blogs/xyz');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        try {
            /**
             * @var RouteDefinitionInterface $definition
             * @var RequestInterface         $request
             */
            $router = new Router();
            $router
                ->addDefinition($definition)
                ->route($request);
        } catch (NotFound $exception) {
            $this->assertSame('Request could not be matched by a route.', $exception->getMessage());
            $this->assertSame($request, $exception->getRequest());
        }
    }

    /**
     * Path part mismatch.
     *
     * Test that route will not match on path part mismatch.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::addDefinition()
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::route()
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::parseUrl()
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::parseStringInteger()
     * @covers \ExtendsSoftware\ExaPHP\Router\Exception\NotFound::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Exception\NotFound::getRequest()
     */
    public function testPathPartMismatch(): void
    {
        $route = $this->createMock(RouteInterface::class);
        $route
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('/blogs');

        $route->expects($this->once())
            ->method('getParameters')
            ->willReturn([]);

        $route
            ->expects($this->once())
            ->method('getValidators')
            ->willReturn([]);

        $definition = $this->createMock(RouteDefinitionInterface::class);
        $definition
            ->expects($this->once())
            ->method('getRoute')
            ->willReturn($route);

        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('toRelative')
            ->willReturn('/pages');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        try {
            /**
             * @var RouteDefinitionInterface $definition
             * @var RequestInterface         $request
             */
            $router = new Router();
            $router
                ->addDefinition($definition)
                ->route($request);
        } catch (NotFound $exception) {
            $this->assertSame('Request could not be matched by a route.', $exception->getMessage());
            $this->assertSame($request, $exception->getRequest());
        }
    }

    /**
     * Invalid query string.
     *
     * Test that exception will be thrown when query string parameter value is invalid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::addDefinition()
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::route()
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::parseUrl()
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::parseStringInteger()
     * @covers \ExtendsSoftware\ExaPHP\Router\Exception\InvalidQueryString::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Exception\InvalidQueryString::getParameter()
     * @covers \ExtendsSoftware\ExaPHP\Router\Exception\InvalidQueryString::getResult()
     */
    public function testInvalidQueryString(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(false);

        $validator = $this->createMock(ValidatorInterface::class);
        $validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn($result);

        $route = $this->createMock(RouteInterface::class);
        $route
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('/blogs?limit');

        $route->expects($this->once())
            ->method('getParameters')
            ->willReturn([]);

        $route
            ->expects($this->once())
            ->method('getValidators')
            ->willReturn([
                'limit' => $validator,
            ]);

        $definition = $this->createMock(RouteDefinitionInterface::class);
        $definition
            ->expects($this->once())
            ->method('getRoute')
            ->willReturn($route);

        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('toRelative')
            ->willReturn('/blogs?limit=abc');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        try {
            /**
             * @var RouteDefinitionInterface $definition
             * @var RequestInterface         $request
             */
            $router = new Router();
            $router
                ->addDefinition($definition)
                ->route($request);
        } catch (InvalidQueryString $exception) {
            $this->assertSame('Value for query string parameter "limit" is invalid.', $exception->getMessage());
            $this->assertSame('limit', $exception->getParameter());
            $this->assertSame($result, $exception->getResult());
        }
    }

    /**
     * Query parameter not allowed.
     *
     * Test that exception will be thrown when query string parameter is not allowed.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::addDefinition()
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::route()
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::parseUrl()
     *                                                          @covers \ExtendsSoftware\ExaPHP\Router\Router::parseStringInteger()
     * @covers \ExtendsSoftware\ExaPHP\Router\Exception\QueryParametersNotAllowed::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Exception\QueryParametersNotAllowed::getParameters
     */
    public function testQueryParametersNotAllowed(): void
    {
        $route = $this->createMock(RouteInterface::class);
        $route
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('/blogs?limit');

        $route->expects($this->once())
            ->method('getParameters')
            ->willReturn([]);

        $route
            ->expects($this->once())
            ->method('getValidators')
            ->willReturn([]);

        $definition = $this->createMock(RouteDefinitionInterface::class);
        $definition
            ->expects($this->once())
            ->method('getRoute')
            ->willReturn($route);

        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('toRelative')
            ->willReturn('/blogs?foo=1&bar=2&baz=3');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        try {
            /**
             * @var RouteDefinitionInterface $definition
             * @var RequestInterface         $request
             */
            $router = new Router();
            $router
                ->addDefinition($definition)
                ->route($request);
        } catch (QueryParametersNotAllowed $exception) {
            $this->assertSame('Query string parameters "foo, bar, baz" are not allowed.', $exception->getMessage());
            $this->assertSame(['foo', 'bar', 'baz'], $exception->getParameters());
        }
    }

    /**
     * Invalid request body.
     *
     * Test that exception will be thrown when request body is invalid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::addDefinition()
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::route()
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::parseUrl()
     *                                                          @covers \ExtendsSoftware\ExaPHP\Router\Router::parseStringInteger()
     * @covers \ExtendsSoftware\ExaPHP\Router\Exception\InvalidRequestBody::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Exception\InvalidRequestBody::getResult()
     */
    public function testInvalidRequestBody(): void
    {
        $body = new stdClass();

        $result = $this->createMock(ResultInterface::class);
        $result
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(false);

        $validator = $this->createMock(ValidatorInterface::class);
        $validator
            ->expects($this->once())
            ->method('validate')
            ->with($body)
            ->willReturn($result);

        $route = $this->createMock(RouteInterface::class);
        $route
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('/blogs');

        $route->expects($this->once())
            ->method('getMethod')
            ->willReturn(Method::GET);

        $route->expects($this->once())
            ->method('getParameters')
            ->willReturn([]);

        $route
            ->expects($this->once())
            ->method('getValidators')
            ->willReturn([
                'body' => $validator,
            ]);

        $definition = $this->createMock(RouteDefinitionInterface::class);
        $definition
            ->expects($this->once())
            ->method('getRoute')
            ->willReturn($route);

        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->once())
            ->method('toRelative')
            ->willReturn('/blogs');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getUri')
            ->willReturn($uri);

        $request
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn(Method::GET);

        $request
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($body);

        try {
            /**
             * @var RouteDefinitionInterface $definition
             * @var RequestInterface         $request
             */
            $router = new Router();
            $router
                ->addDefinition($definition)
                ->route($request);
        } catch (InvalidRequestBody $exception) {
            $this->assertSame('Request body is invalid.', $exception->getMessage());
            $this->assertSame($result, $exception->getResult());
        }
    }

    /**
     * Method not allowed.
     *
     * Test that exception will be thrown when a route can be matched but request method is not allowed.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::addDefinition()
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::route()
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::parseUrl()
     *                                                          @covers \ExtendsSoftware\ExaPHP\Router\Router::parseStringInteger()
     * @covers \ExtendsSoftware\ExaPHP\Router\Exception\MethodNotAllowed::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Exception\MethodNotAllowed::getMethod()
     * @covers \ExtendsSoftware\ExaPHP\Router\Exception\MethodNotAllowed::getAllowedMethods()
     */
    public function testMethodNotAllowed(): void
    {
        $route = $this->createMock(RouteInterface::class);
        $route
            ->expects($this->exactly(2))
            ->method('getPath')
            ->willReturn('/');

        $route
            ->expects($this->exactly(4))
            ->method('getMethod')
            ->willReturnOnConsecutiveCalls(
                Method::POST,
                Method::POST,
                Method::PUT,
                Method::PUT,
            );

        $route
            ->expects($this->exactly(2))
            ->method('getParameters')
            ->willReturn([]);

        $route
            ->expects($this->exactly(2))
            ->method('getValidators')
            ->willReturn([]);

        $definition = $this->createMock(RouteDefinitionInterface::class);
        $definition
            ->expects($this->exactly(2))
            ->method('getRoute')
            ->willReturn($route);

        $uri = $this->createMock(UriInterface::class);
        $uri
            ->expects($this->exactly(2))
            ->method('toRelative')
            ->willReturn('/');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->exactly(2))
            ->method('getUri')
            ->willReturn($uri);

        $request
            ->expects($this->exactly(3))
            ->method('getMethod')
            ->willReturn(Method::GET);

        try {
            /**
             * @var RouteDefinitionInterface $definition
             * @var RequestInterface         $request
             */
            $router = new Router();
            $router
                ->addDefinition($definition, $definition)
                ->route($request);
        } catch (MethodNotAllowed $exception) {
            $this->assertSame('Method "GET" is not allowed.', $exception->getMessage());
            $this->assertSame(Method::GET, $exception->getMethod());
            $this->assertSame([
                Method::POST,
                Method::PUT,
            ], $exception->getAllowedMethods());
        }
    }

    /**
     * Not found.
     *
     * Test that an exception will be thrown when exception can not be found.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::route()
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::parseUrl()
     *                                                          @covers \ExtendsSoftware\ExaPHP\Router\Router::parseStringInteger()
     * @covers \ExtendsSoftware\ExaPHP\Router\Exception\NotFound::__construct()
     */
    public function testNotFound(): void
    {
        $this->expectException(NotFound::class);
        $this->expectExceptionMessage('Request could not be matched by a route.');

        $request = $this->createMock(RequestInterface::class);

        /**
         * @var RequestInterface $request
         */
        $router = new Router();
        $router->route($request);
    }

    /**
     * Assemble.
     *
     * Test that route will be assembled for given name and parameters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::addDefinition()
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::assemble()
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::parseUrl()
     *                                                          @covers \ExtendsSoftware\ExaPHP\Router\Router::parseStringInteger()
     */
    public function testAssemble(): void
    {
        $route = $this->createMock(RouteInterface::class);
        $route
            ->expects($this->once())
            ->method('getName')
            ->willReturn('blogs/blog');

        $route
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('/blogs/:blogId/comments?page=1&limit=10');

        $route
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn(Method::GET);

        $definition = $this->createMock(RouteDefinitionInterface::class);
        $definition
            ->expects($this->once())
            ->method('getRoute')
            ->willReturn($route);

        /**
         * @var RouteDefinitionInterface $definition
         */
        $router = new Router();
        $request = $router
            ->addDefinition($definition)
            ->assemble('blogs/blog', [
                'blogId' => 1234,
                'limit' => 20,
                'page' => 2,
            ]);

        $this->assertInstanceOf(RequestInterface::class, $request);
        $this->assertSame(Method::GET, $request->getMethod());
        $this->assertSame('/blogs/1234/comments?page=2&limit=20', $request->getUri()->toRelative());
    }

    /**
     * Route not found.
     *
     * Test that exception will be thrown when route with name can not be found.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::addDefinition()
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::assemble()
     * @covers \ExtendsSoftware\ExaPHP\Router\Exception\RouteNotFound::__construct()
     */
    public function testRouteNotFound(): void
    {
        $this->expectException(RouteNotFound::class);
        $this->expectExceptionMessage('Route for name "index" can not be found.');

        $route = $this->createMock(RouteInterface::class);
        $route
            ->expects($this->once())
            ->method('getName')
            ->willReturn('blogs/blog');

        $definition = $this->createMock(RouteDefinitionInterface::class);
        $definition
            ->expects($this->once())
            ->method('getRoute')
            ->willReturn($route);

        /**
         * @var RouteDefinitionInterface $definition
         */
        $router = new Router();
        $router
            ->addDefinition($definition)
            ->assemble('index');
    }

    /**
     * Path parameter missing.
     *
     * Test that exception will be thrown when route with name can not be found.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::addDefinition()
     * @covers \ExtendsSoftware\ExaPHP\Router\Router::assemble()
     * @covers \ExtendsSoftware\ExaPHP\Router\Exception\PathParameterMissing::__construct()
     */
    public function testPathParameterMissing(): void
    {
        $this->expectException(PathParameterMissing::class);
        $this->expectExceptionMessage('Failed to assemble route, path parameter "blogId" is missing.');

        $route = $this->createMock(RouteInterface::class);
        $route
            ->expects($this->once())
            ->method('getName')
            ->willReturn('blogs/blog');

        $route
            ->expects($this->once())
            ->method('getPath')
            ->willReturn('/blogs/:blogId/comments?page=1&limit=10');

        $definition = $this->createMock(RouteDefinitionInterface::class);
        $definition
            ->expects($this->once())
            ->method('getRoute')
            ->willReturn($route);

        /**
         * @var RouteDefinitionInterface $definition
         */
        $router = new Router();
        $router
            ->addDefinition($definition)
            ->assemble('blogs/blog');
    }
}
