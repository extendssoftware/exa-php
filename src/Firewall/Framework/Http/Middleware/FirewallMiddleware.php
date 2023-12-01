<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Firewall\Framework\Http\Middleware;

use ExtendsSoftware\ExaPHP\Firewall\FirewallInterface;
use ExtendsSoftware\ExaPHP\Firewall\Framework\ProblemDetails\ForbiddenProblemDetails;
use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\MiddlewareInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\Response;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;

readonly class FirewallMiddleware implements MiddlewareInterface
{
    /**
     * FirewallMiddleware constructor.
     *
     * @param FirewallInterface $firewall
     */
    public function __construct(private FirewallInterface $firewall)
    {
    }

    /**
     * @inheritDoc
     */
    public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
    {
        if (!$this->firewall->isAllowed($request)) {
            return (new Response())->withBody(
                new ForbiddenProblemDetails($request)
            );
        }

        return $chain->proceed($request);
    }
}
