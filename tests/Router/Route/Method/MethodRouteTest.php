<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route\Method;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Router\Route\Method\Exception\InvalidRequestBody;
use ExtendsSoftware\ExaPHP\Router\Route\Method\Exception\MethodNotAllowed;
use ExtendsSoftware\ExaPHP\Router\Route\RouteInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class MethodRouteTest extends TestCase
{
    /**
     * Match.
     *
     * Test that POST method will be matched and an instance of RouteMatchInterface will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Method\MethodRoute::factory()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Method\MethodRoute::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Method\MethodRoute::match()
     */
    public function testMatch(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn('POST');

        $request
            ->expects($this->exactly(2))
            ->method('getBody')
            ->willReturn(['foo' => 'bar']);

        $result = $this->createMock(ResultInterface::class);
        $result
            ->expects($this->exactly(2))
            ->method('isValid')
            ->willReturn(true);

        $validator = $this->createMock(ValidatorInterface::class);
        $validator
            ->expects($this->exactly(2))
            ->method('validate')
            ->with(['foo' => 'bar'])
            ->willReturn($result);

        /**
         * @var RequestInterface $request
         */
        $method = new MethodRoute('POST', [
            'foo' => 'bar',
        ], [$validator, $validator]);
        $match = $method->match($request, 5);

        $this->assertIsObject($match);
        $this->assertSame(5, $match->getPathOffset());
        $this->assertSame([
            'foo' => 'bar',
        ], $match->getParameters());
    }

    /**
     * Match without parameters.
     *
     * Test that POST method will be matched and an instance of RouteMatchInterface will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Method\MethodRoute::factory()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Method\MethodRoute::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Method\MethodRoute::match()
     */
    public function testMatchWithoutParameters(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn('POST');

        /**
         * @var RequestInterface $request
         */
        $method = new MethodRoute('POST');
        $match = $method->match($request, 5);

        $this->assertIsObject($match);
        $this->assertSame(5, $match->getPathOffset());
        $this->assertEmpty($match->getParameters());
    }

    /**
     * Method not allowed.
     *
     * Test that method GET is not allowed and an exception will be thrown.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Method\MethodRoute::factory()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Method\MethodRoute::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Method\MethodRoute::match()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Method\Exception\MethodNotAllowed::__construct()
     */
    public function testMethodNotAllowed(): void
    {
        $this->expectException(MethodNotAllowed::class);
        $this->expectExceptionMessage('Method "GET" is not allowed.');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn('GET');

        /**
         * @var RequestInterface $request
         */
        $method = new MethodRoute('POST');
        $method->match($request, 5);
    }

    /**
     * Unprocessable entity.
     *
     * Test that request body is invalid and an exception will be thrown.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Method\MethodRoute::factory()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Method\MethodRoute::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Method\MethodRoute::match()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Method\Exception\InvalidRequestBody::__construct()
     */
    public function testUnprocessableEntity(): void
    {
        $this->expectException(InvalidRequestBody::class);
        $this->expectExceptionMessage('Request body is invalid.');

        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getMethod')
            ->willReturn('POST');

        $request
            ->expects($this->once())
            ->method('getBody')
            ->willReturn(['foo' => 'bar']);

        $result = $this->createMock(ResultInterface::class);
        $result
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(false);

        $validator = $this->createMock(ValidatorInterface::class);
        $validator
            ->expects($this->once())
            ->method('validate')
            ->with(['foo' => 'bar'])
            ->willReturn($result);

        /**
         * @var RequestInterface $request
         */
        $method = new MethodRoute('POST', validators: [
            $validator,
        ]);
        $method->match($request, 5);
    }

    /**
     * Assemble.
     *
     * Test that assemble method will return request.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Method\MethodRoute::assemble()
     */
    public function testAssemble(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $request
            ->expects($this->once())
            ->method('withMethod')
            ->with('POST')
            ->willReturnSelf();

        /**
         * @var RequestInterface $request
         */
        $method = new MethodRoute('POST');
        $this->assertSame($request, $method->assemble($request, [], []));
    }

    /**
     * Factory.
     *
     * Test that factory will return an instance of RouteInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Method\MethodRoute::factory()
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Method\MethodRoute::__construct()
     */
    public function testFactory(): void
    {
        $validator = $this->createMock(ValidatorInterface::class);

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->exactly(2))
            ->method('getService')
            ->withConsecutive(
                [ValidatorInterface::class, ['foo' => 'bar',]],
                [ValidatorInterface::class, []]
            )
            ->willReturn($validator);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $route = MethodRoute::factory(MethodRoute::class, $serviceLocator, [
            'method' => 'POST',
            'parameters' => [
                'foo' => 'bar',
            ],
            'validators' => [
                [
                    'name' => ValidatorInterface::class,
                    'options' => [
                        'foo' => 'bar',
                    ],
                ],
                ValidatorInterface::class, // Short syntax will be converted to array with 'name' property.
            ],
        ]);

        $this->assertInstanceOf(RouteInterface::class, $route);
    }

    /**
     * Methods.
     *
     * Test that constants contain correct methods.
     *
     * @covers \ExtendsSoftware\ExaPHP\Router\Route\Method\MethodRoute
     */
    public function testMethods(): void
    {
        $this->assertSame('OPTIONS', MethodRoute::METHOD_OPTIONS);
        $this->assertSame('GET', MethodRoute::METHOD_GET);
        $this->assertSame('HEAD', MethodRoute::METHOD_HEAD);
        $this->assertSame('POST', MethodRoute::METHOD_POST);
        $this->assertSame('PUT', MethodRoute::METHOD_PUT);
        $this->assertSame('PATCH', MethodRoute::METHOD_PATCH);
        $this->assertSame('DELETE', MethodRoute::METHOD_DELETE);
        $this->assertSame('TRACE', MethodRoute::METHOD_TRACE);
        $this->assertSame('CONNECT', MethodRoute::METHOD_CONNECT);
    }
}
