<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\RateLimiting\Framework\Http\Middleware;

use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\MiddlewareInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatchInterface;

class RateLimitingMiddleware implements MiddlewareInterface
{
    /**
     * @inheritDoc
     */
    public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
    {
        $match = $request->getAttribute('routeMatch');
        if ($match instanceof RouteMatchInterface) {
            if ($match->getParameter('rate_limiting')) {
                // ...
            }
        }

        return $chain->proceed($request);
    }
}
