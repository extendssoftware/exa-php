<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application\Framework\Http\Middleware;

use ExtendsSoftware\ExaPHP\Application\Framework\ProblemDetails\InternalServerErrorProblemDetails;
use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\MiddlewareInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\Response;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use Throwable;

class InternalServerErrorMiddleware implements MiddlewareInterface
{
    /**
     * @inheritDoc
     */
    public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
    {
        try {
            return $chain->proceed($request);
        } catch (Throwable) {
            return (new Response())->withBody(
                new InternalServerErrorProblemDetails($request)
            );
        }
    }
}
