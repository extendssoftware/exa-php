<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Http\Request;

use ExtendsSoftware\ExaPHP\Http\Request\Method\Method;
use ExtendsSoftware\ExaPHP\Http\Request\Uri\UriInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\Text\UuidValidator;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use TypeError;

class RequestTest extends TestCase
{
    /**
     * Default $_SERVER global.
     *
     * @var array<string, string>
     */
    protected static array $defaultServer;

    /**
     * Save default $_SERVER global.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        static::$defaultServer = $_SERVER;
    }

    /**
     * Reset $_SERVER global.
     *
     * @return void
     */
    public function tearDown(): void
    {
        $_SERVER = static::$defaultServer;
    }

    /**
     * Get methods.
     *
     * Test that get methods will return the correct php://input abd $_SERVER values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::fromEnvironment()
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::getAttributes()
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::getId()
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::getBody()
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::getHeaders()
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::getServerParameters()
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::getMethod()
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::getUri()
     */
    public function testCanCreateNewInstanceFromEnvironment(): void
    {
        $body = [
            'foo' => 'qux',
        ];

        $root = vfsStream::setup('root', null, [
            'input' => json_encode($body),
        ]);

        $environment['HTTP_HOST'] = 'www.example.com';
        $environment['SERVER_PORT'] = 80;
        $environment['REQUEST_METHOD'] = 'POST';
        $environment['QUERY_STRING'] = 'baz=qux';
        $environment['REQUEST_URI'] = '/foo/bar';
        $environment['HTTP_CONTENT_TYPE'] = 'application/json';

        $request = Request::fromEnvironment($environment, fopen($root->url() . '/input', 'r'));

        $this->assertSame([], $request->getAttributes());
        $this->assertTrue((new UuidValidator())->validate($request->getId())->isValid());
        $this->assertEquals((object)$body, $request->getBody());
        $this->assertSame([
            'Host' => 'www.example.com',
            'Content-Type' => 'application/json',
        ], $request->getHeaders());
        $this->assertSame([
            'Server-Port' => 80,
            'Request-Method' => 'POST',
            'Query-String' => 'baz=qux',
            'Request-Uri' => '/foo/bar',
        ], $request->getServerParameters());
        $this->assertSame(Method::POST, $request->getMethod());
        $this->assertIsObject($request->getUri());
    }

    /**
     * With methods.
     *
     * Test that with methods will set the correct value.
     *
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::withAttributes()
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::withId()
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::withBody()
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::withHeaders()
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::withServerParameters()
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::withMethod()
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::withUri()
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::getAttributes()
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::getAttribute()
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::getBody()
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::getHeaders()
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::getHeader()
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::getServerParameters()
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::getServerParameter()
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::getMethod()
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::getUri()
     */
    public function testWithMethods(): void
    {
        $uri = $this->createMock(UriInterface::class);

        /**
         * @var UriInterface $uri
         */
        $request = (new Request())
            ->withAttributes(['foo' => 'bar'])
            ->withId('b73a7424-9994-477c-abbb-6275ca53a136')
            ->withBody(['baz' => 'qux'])
            ->withHeaders(['qux' => 'quux'])
            ->withServerParameters(['bar' => 'qux'])
            ->withMethod(Method::POST)
            ->withUri($uri);

        $this->assertSame('b73a7424-9994-477c-abbb-6275ca53a136', $request->getId());
        $this->assertSame(['foo' => 'bar'], $request->getAttributes());
        $this->assertSame(['baz' => 'qux'], $request->getBody());
        $this->assertSame(['qux' => 'quux'], $request->getHeaders());
        $this->assertSame(['bar' => 'qux'], $request->getServerParameters());
        $this->assertSame(Method::POST, $request->getMethod());
        $this->assertSame($uri, $request->getUri());
        $this->assertSame('quux', $request->getHeader('qux'));
        $this->assertSame('qux', $request->getServerParameter('bar'));
        $this->assertSame('bar', $request->getAttribute('foo'));

        // Default return values.
        $this->assertSame('qux', $request->getHeader('bar', 'qux'));
        $this->assertSame('quux', $request->getServerParameter('baz', 'quux'));
        $this->assertSame('quux', $request->getAttribute('bar', 'quux'));
    }

    /**
     * And attribute.
     *
     * Test that a single attribute parameter can be set.
     *
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::andAttribute()
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::getAttributes()
     */
    public function testCanMergeWithAttribute(): void
    {
        $request = (new Request())
            ->andAttribute('foo', 'bar')
            ->andAttribute('qux', 'quux');

        $this->assertSame([
            'foo' => 'bar',
            'qux' => 'quux',
        ], $request->getAttributes());
    }

    /**
     * And header.
     *
     * Test that a single header parameter can be set.
     *
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::andHeader()
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::getHeaders()
     */
    public function testAndHeader(): void
    {
        $request = (new Request())
            ->andHeader('foo', 'bar')
            ->andHeader('foo', 'baz')
            ->andHeader('qux', 'quux');

        $this->assertSame([
            'foo' => [
                'bar',
                'baz',
            ],
            'qux' => 'quux',
        ], $request->getHeaders());
    }

    /**
     * With headers.
     *
     * Test that already set header will be overwritten.
     *
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::andHeader()
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::withHeader()
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::getHeaders()
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::getHeader()
     */
    public function testWithHeader(): void
    {
        $request = (new Request())
            ->andHeader('foo', 'bar')
            ->andHeader('foo', 'baz')
            ->withHeader('foo', 'qux')
            ->andHeader('qux', 'quux');

        $this->assertSame([
            'foo' => 'qux',
            'qux' => 'quux',
        ], $request->getHeaders());
    }

    /**
     * Get URI.
     *
     * Test that default URI object will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::getUri()
     */
    public function testGetUri(): void
    {
        $uri = (new Request())->getUri();

        $this->assertIsObject($uri);
    }

    /**
     * Invalid body.
     *
     * Test that invalid body can not be parsed and an exception will be thrown.
     *
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::fromEnvironment()
     */
    public function testInvalidBody(): void
    {
        $root = vfsStream::setup('root', null, [
            'input' => '{"foo":"qux"',
        ]);

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['HTTP_HOST'] = 'www.extends.nl';
        $_SERVER['HTTP_CONTENT_TYPE'] = 'application/json';
        $_SERVER['SERVER_PORT'] = 80;
        $_SERVER['REQUEST_URI'] = '/';

        $request = Request::fromEnvironment($_SERVER, fopen($root->url() . '/input', 'r'));

        $this->assertNull($request->getBody());
    }

    /**
     * Empty body.
     *
     * Test that empty body is allowed for request.
     *
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::fromEnvironment()
     */
    public function testEmptyBody(): void
    {
        $root = vfsStream::setup('root', null, [
            'input' => '',
        ]);

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['HTTP_HOST'] = 'www.extends.nl';
        $_SERVER['SERVER_PORT'] = 80;
        $_SERVER['REQUEST_URI'] = '/';

        $request = Request::fromEnvironment($_SERVER, fopen($root->url() . '/input', 'r'));

        $this->assertNull($request->getBody());
    }

    /**
     * Factory.
     *
     * Test that factory will return an instance of RequestInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::factory()
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::fromEnvironment()
     */
    public function testFactory(): void
    {
        $root = vfsStream::setup('root', null, [
            'input' => '',
        ]);

        $environment['REQUEST_METHOD'] = 'GET';
        $environment['HTTP_HOST'] = 'www.extends.nl';
        $environment['SERVER_PORT'] = 80;
        $environment['REQUEST_URI'] = '/';

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $request = Request::factory(RequestInterface::class, $serviceLocator, [
            'environment' => $environment,
            'stream' => fopen($root->url() . '/input', 'r'),
        ]);

        $this->assertInstanceOf(RequestInterface::class, $request);
    }

    /**
     * Stream not resource.
     *
     * Test that an exception will be thrown when filename can not be opened for reading.
     *
     * @covers \ExtendsSoftware\ExaPHP\Http\Request\Request::fromEnvironment()
     */
    public function testStreamNotResource(): void
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('Stream must be of type resource, string given.');

        Request::fromEnvironment([], 'foo');
    }
}
