<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authentication\Framework\Http\Middleware;

use ExtendsSoftware\ExaPHP\Authentication\AuthenticatorInterface;
use ExtendsSoftware\ExaPHP\Authentication\Framework\ProblemDetails\UnauthorizedProblemDetails;
use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\MiddlewareInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\Response;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;

readonly class AuthenticationMiddleware implements MiddlewareInterface
{
    /**
     * AuthenticationHeaderMiddleware constructor.
     *
     * @param AuthenticatorInterface $authenticator
     */
    public function __construct(private AuthenticatorInterface $authenticator)
    {
    }

    /**
     * @inheritDoc
     */
    public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
    {
        $identity = $this->authenticator->authenticate($request);
        if ($identity instanceof IdentityInterface) {
            $request = $request->andAttribute('identity', $identity);
        }

        if ($identity === false) {
            return (new Response())->withBody(
                new UnauthorizedProblemDetails($request)
            );
        }

        return $chain->proceed($request);
    }
}
