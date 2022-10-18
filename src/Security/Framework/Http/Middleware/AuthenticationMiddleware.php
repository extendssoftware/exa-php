<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Security\Framework\Http\Middleware;

use ExtendsSoftware\ExaPHP\Authentication\Header\Header;
use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\MiddlewareInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\Response;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Security\Framework\ProblemDetails\UnauthorizedProblemDetails;
use ExtendsSoftware\ExaPHP\Security\SecurityServiceInterface;

class AuthenticationMiddleware implements MiddlewareInterface
{
    /**
     * Pattern to detect scheme and credentials.
     *
     * @var string
     */
    private string $pattern = '/^(?P<scheme>[^\s]+)\s(?P<credentials>[^\s]+)$/';

    /**
     * AuthenticationHeaderMiddleware constructor.
     *
     * @param SecurityServiceInterface $securityService
     */
    public function __construct(private readonly SecurityServiceInterface $securityService)
    {
    }

    /**
     * @inheritDoc
     */
    public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
    {
        $authorization = $request->getHeader('Authorization');
        if (is_string($authorization)) {
            if (!preg_match($this->pattern, $authorization, $matches) ||
                !$this->securityService->authenticate(new Header($matches['scheme'], $matches['credentials']))
            ) {
                return (new Response())->withBody(
                    new UnauthorizedProblemDetails($request)
                );
            }
        }

        return $chain->proceed($request);
    }
}
