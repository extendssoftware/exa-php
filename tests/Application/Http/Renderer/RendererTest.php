<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application\Http\Renderer;

use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use Generator;
use PHPUnit\Framework\TestCase;

use function header_remove;
use function http_response_code;
use function ob_end_clean;
use function ob_get_contents;
use function ob_start;
use function xdebug_get_headers;

class RendererTest extends TestCase
{
    /**
     * Start output buffer.
     *
     * @return void
     */
    protected function setUp(): void
    {
        ob_start();

        parent::setUp();
    }

    /**
     * Clean output buffer, remove set headers and reset the HTTP response code.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        ob_end_clean();
        header_remove();
        http_response_code();

        parent::tearDown();
    }

    /**
     * Render.
     *
     * Test that response will be rendered: headers sent, body encoded and HTTP status code set.
     *
     * @covers \ExtendsSoftware\ExaPHP\Application\Http\Renderer\Renderer::render()
     */
    public function testRender(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn('{"foo":"bar"}');

        $response
            ->expects($this->once())
            ->method('getHeaders')
            ->willReturn([
                'Accept' => [
                    'text/html',
                    'application/xhtml+xml',
                    'application/xml;q=0.9',
                    '*/*;q=0.8',
                ],
                'Content-Type' => 'application/json',
                'Content-Length' => '13',
            ]);

        $response
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        /**
         * @var ResponseInterface $response
         */
        $renderer = new Renderer();

        $renderer->render($response);
        $output = ob_get_contents();

        $this->assertSame('{"foo":"bar"}', $output);
        $this->assertSame([
            'Accept: text/html, application/xhtml+xml, application/xml;q=0.9, */*;q=0.8',
            'Content-Type: application/json',
            'Content-Length: 13',
        ], xdebug_get_headers());
        $this->assertSame(200, http_response_code());
    }

    /**
     * Render generator.
     *
     * Test that a generator will be looped and every chunk will be output.
     *
     * @covers \ExtendsSoftware\ExaPHP\Application\Http\Renderer\Renderer::render()
     */
    public function testRenderGenerator(): void
    {
        $generator = function (): Generator {
            foreach (['123', '456', '789', '0'] as $chunk) {
                yield $chunk;
            }
        };

        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($generator());

        $response
            ->expects($this->once())
            ->method('getHeaders')
            ->willReturn([
                'Content-Type' => 'application/octet-stream',
            ]);

        $response
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        /**
         * @var ResponseInterface $response
         */
        $renderer = new Renderer();

        $renderer->render($response);
        $output = ob_get_contents();

        $this->assertSame('1234567890', $output);
        $this->assertSame([
            'Content-Type: application/octet-stream',
        ], xdebug_get_headers());
        $this->assertSame(200, http_response_code());
    }

    /**
     * Render.
     *
     * Test that an empty body will not be sent.
     *
     * @covers \ExtendsSoftware\ExaPHP\Application\Http\Renderer\Renderer::render()
     */
    public function testEmptyBody(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn(null);

        $response
            ->expects($this->once())
            ->method('getHeaders')
            ->willReturn([
                'Accept-Charset' => 'utf-8',
                'Content-Type' => 'application/json',
                'Content-Length' => '0',
            ]);

        $response
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        /**
         * @var ResponseInterface $response
         */
        $renderer = new Renderer();

        $renderer->render($response);
        $output = ob_get_contents();

        http_response_code(200);

        $this->assertSame('', $output);
        $this->assertSame([
            'Accept-Charset: utf-8',
            'Content-Type: application/json',
            'Content-Length: 0',
        ], xdebug_get_headers());
        $this->assertSame(200, http_response_code());
    }
}
