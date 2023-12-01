<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application\Http\Renderer;

use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;

interface RendererInterface
{
    /**
     * Render response.
     *
     * @param ResponseInterface $response
     *
     * @return void
     */
    public function render(ResponseInterface $response): void;
}
