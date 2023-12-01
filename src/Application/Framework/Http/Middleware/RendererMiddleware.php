<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application\Framework\Http\Middleware;

use ExtendsSoftware\ExaPHP\Application\Http\Renderer\RendererInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\MiddlewareInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;

class RendererMiddleware implements MiddlewareInterface
{
    /**
     * Renderer
     *
     * @var RendererInterface
     */
    private RendererInterface $renderer;

    /**
     * RendererMiddleware constructor.
     *
     * @param RendererInterface $renderer
     */
    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @inheritDoc
     */
    public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
    {
        $response = $chain->proceed($request);
        $this->renderer->render($response);

        return $response;
    }
}
