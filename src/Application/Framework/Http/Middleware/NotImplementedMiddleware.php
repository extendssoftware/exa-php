<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application\Framework\Http\Middleware;

use ExtendsSoftware\ExaPHP\Application\Framework\ProblemDetails\NotImplementedProblemDetails;
use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\MiddlewareInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\Response;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;

class NotImplementedMiddleware implements MiddlewareInterface
{
    /**
     * @inheritDoc
     */
    public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
    {
        return (new Response())->withBody(
            new NotImplementedProblemDetails($request)
        );
    }
}
