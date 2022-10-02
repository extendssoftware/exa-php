<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application\Http\Renderer;

use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use PHPUnit\Framework\TestCase;

class RendererTest extends TestCase
{
    /**
     * Render.
     *
     * Test that response will be rendered: headers sent, body encoded and HTTP status code set.
     *
     * @covers \ExtendsSoftware\ExaPHP\Application\Http\Renderer\Renderer::render()
     * @runInSeparateProcess
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

        ob_start();
        $renderer->render($response);
        $output = ob_get_clean();

        $this->assertSame('{"foo":"bar"}', $output);
        $this->assertSame([
            'Accept: text/html, application/xhtml+xml, application/xml;q=0.9, */*;q=0.8',
            'Content-Type: application/json',
            'Content-Length: 13',
        ], xdebug_get_headers());
        $this->assertSame(200, http_response_code());
    }

    /**
     * Render.
     *
     * Test that an empty body will not be sent.
     *
     * @covers \ExtendsSoftware\ExaPHP\Application\Http\Renderer\Renderer::render()
     * @runInSeparateProcess
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

        ob_start();
        $renderer->render($response);
        $output = ob_get_clean();

        $this->assertSame('', $output);
        $this->assertSame([
            'Accept-Charset: utf-8',
            'Content-Type: application/json',
            'Content-Length: 0',
        ], xdebug_get_headers());
        $this->assertSame(200, http_response_code());
    }
}
