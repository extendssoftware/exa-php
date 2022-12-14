<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Http\Response;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    /**
     * Get parameters.
     *
     * Test that get parameters will return default values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Http\Response\Response::getBody()
     * @covers \ExtendsSoftware\ExaPHP\Http\Response\Response::getHeaders()
     * @covers \ExtendsSoftware\ExaPHP\Http\Response\Response::getStatusCode()
     */
    public function testGetMethods(): void
    {
        $response = new Response();

        $this->assertNull($response->getBody());
        $this->assertSame([], $response->getHeaders());
        $this->assertSame(200, $response->getStatusCode());
    }

    /**
     * And methods.
     *
     * Test that new responses will be returned with the correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Http\Response\Response::withHeaders()
     * @covers \ExtendsSoftware\ExaPHP\Http\Response\Response::andHeader()
     * @covers \ExtendsSoftware\ExaPHP\Http\Response\Response::getHeaders()
     */
    public function testAndMethods(): void
    {
        $response = (new Response())
            ->andHeader('baz', 'qux')
            ->andHeader('baz', 'bar')
            ->andHeader('foo', 'bar');

        $this->assertSame([
            'baz' => [
                'qux',
                'bar',
            ],
            'foo' => 'bar',
        ], $response->getHeaders());
    }

    /**
     * With methods.
     *
     * Test that with methods can set value and return copy of response.
     *
     * @covers \ExtendsSoftware\ExaPHP\Http\Response\Response::withBody()
     * @covers \ExtendsSoftware\ExaPHP\Http\Response\Response::withHeaders()
     * @covers \ExtendsSoftware\ExaPHP\Http\Response\Response::withStatusCode()
     * @covers \ExtendsSoftware\ExaPHP\Http\Response\Response::getBody()
     * @covers \ExtendsSoftware\ExaPHP\Http\Response\Response::getHeaders()
     * @covers \ExtendsSoftware\ExaPHP\Http\Response\Response::getHeader()
     * @covers \ExtendsSoftware\ExaPHP\Http\Response\Response::getStatusCode()
     */
    public function testWithMethods(): void
    {
        $response = (new Response())
            ->withBody(['foo' => 'bar'])
            ->withHeaders(['baz' => 'qux'])
            ->withStatusCode(201);

        $this->assertSame(['foo' => 'bar'], $response->getBody());
        $this->assertSame(['baz' => 'qux'], $response->getHeaders());
        $this->assertSame('qux', $response->getHeader('baz', 'quux'));
        $this->assertSame(201, $response->getStatusCode());
    }

    /**
     * With headers.
     *
     * Test that already set header will be overwritten.
     *
     * @covers \ExtendsSoftware\ExaPHP\Http\Response\Response::andHeader()
     * @covers \ExtendsSoftware\ExaPHP\Http\Response\Response::withHeader()
     * @covers \ExtendsSoftware\ExaPHP\Http\Response\Response::getHeaders()
     */
    public function testWithHeader(): void
    {
        $response = (new Response())
            ->andHeader('foo', 'bar')
            ->andHeader('foo', 'baz')
            ->withHeader('foo', 'qux')
            ->andHeader('qux', 'quux');

        $this->assertSame([
            'foo' => 'qux',
            'qux' => 'quux',
        ], $response->getHeaders());
    }

    /**
     * Factory.
     *
     * Test that factory will return an instance of ResponseInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Http\Response\Response::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $request = Response::factory(ResponseInterface::class, $serviceLocator);

        $this->assertInstanceOf(ResponseInterface::class, $request);
    }
}
