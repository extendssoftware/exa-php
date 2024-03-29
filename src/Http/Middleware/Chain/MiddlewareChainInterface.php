<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Http\Middleware\Chain;

use ExtendsSoftware\ExaPHP\Http\Middleware\MiddlewareException;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;

interface MiddlewareChainInterface
{
    /**
     * Proceed middleware chain with request.
     *
     * To avoid (serious) side effects, the chain should not be called more the once.
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     * @throws MiddlewareException
     */
    public function proceed(RequestInterface $request): ResponseInterface;
}
