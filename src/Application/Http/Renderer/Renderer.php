<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application\Http\Renderer;

use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use Generator;

use function flush;
use function header;
use function http_response_code;
use function implode;
use function is_array;
use function sprintf;

class Renderer implements RendererInterface
{
    /**
     * @inheritDoc
     */
    public function render(ResponseInterface $response): void
    {
        foreach ($response->getHeaders() as $header => $value) {
            if (is_array($value)) {
                $value = implode(', ', $value);
            }

            header(
                sprintf(
                    '%s: %s',
                    $header,
                    $value
                )
            );
        }

        http_response_code($response->getStatusCode());

        $body = $response->getBody();
        if ($body instanceof Generator) {
            foreach ($body as $part) {
                echo $part;

                // Flush part of output to the browser to reduce memory usage and allow for streaming the body content.
                flush();
            }
        } else {
            echo $body;
        }
    }
}
