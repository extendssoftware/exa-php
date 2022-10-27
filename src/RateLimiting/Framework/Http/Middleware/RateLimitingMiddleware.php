<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\RateLimiting\Framework\Http\Middleware;

use ExtendsSoftware\ExaPHP\Authorization\Permission\Permission;
use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\MiddlewareInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\Response;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\RateLimiting\Framework\ProblemDetails\TooManyRequestsProblemDetails;
use ExtendsSoftware\ExaPHP\RateLimiting\Quota\QuotaInterface;
use ExtendsSoftware\ExaPHP\RateLimiting\RateLimiterInterface;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatchInterface;

class RateLimitingMiddleware implements MiddlewareInterface
{
    /**
     * RateLimitingMiddleware constructor.
     *
     * @param RateLimiterInterface $rateLimiter
     */
    public function __construct(private readonly RateLimiterInterface $rateLimiter)
    {
    }

    /**
     * @inheritDoc
     */
    public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
    {
        $match = $request->getAttribute('routeMatch');
        if ($match instanceof RouteMatchInterface) {
            $permission = new Permission($match->getName());
            $identity = $request->getAttribute('identity');
            $quota = $this->rateLimiter->consume($permission, $identity);
            if ($quota?->isConsumed() === false) {
                $response = (new Response())->withBody(
                    new TooManyRequestsProblemDetails($request, $quota)
                );
            }
        }

        if (!isset($response)) {
            $response = $chain->proceed($request);
        }

        if (isset($quota)) {
            $response = $this->addRateLimitHeaders($response, $quota);
        }

        return $response;
    }

    /**
     * Add rate limit headers to response.
     *
     * @param ResponseInterface $response
     * @param QuotaInterface    $quota
     *
     * @return ResponseInterface
     */
    private function addRateLimitHeaders(ResponseInterface $response, QuotaInterface $quota): ResponseInterface
    {
        return $response
            ->andHeader('X-RateLimit-Limit', (string)$quota->getLimit())
            ->andHeader('X-RateLimit-Remaining', (string)$quota->getRemaining())
            ->andHeader('X-RateLimit-Reset', (string)$quota->getReset());
    }
}
