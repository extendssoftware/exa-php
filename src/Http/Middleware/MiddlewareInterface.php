<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Http\Middleware;

use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;

interface MiddlewareInterface
{
    /**
     * Process middleware.
     *
     * The middleware must call proceed() on chain with a RequestInterface object. It is recommended to use request
     * for this call.
     *
     * The middleware must return a ResponseInterface object. It is recommended to return the response from the chain
     * proceed() method.
     *
     * Both request and response may be modified by creating a new instance.
     *
     * @param RequestInterface         $request
     * @param MiddlewareChainInterface $chain
     *
     * @return ResponseInterface
     * @throws MiddlewareException
     */
    public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface;
}
