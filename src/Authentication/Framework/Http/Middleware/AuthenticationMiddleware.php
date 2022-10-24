<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authentication\Framework\Http\Middleware;

use ExtendsSoftware\ExaPHP\Authentication\AuthenticatorInterface;
use ExtendsSoftware\ExaPHP\Authentication\Framework\ProblemDetails\UnauthorizedProblemDetails;
use ExtendsSoftware\ExaPHP\Authentication\Header\Header;
use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\MiddlewareInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\Response;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Identity\IdentityInterface;
use ExtendsSoftware\ExaPHP\Identity\Storage\StorageInterface;

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
     * @param AuthenticatorInterface $authenticator
     * @param StorageInterface       $storage
     */
    public function __construct(
        private readonly AuthenticatorInterface $authenticator,
        private readonly StorageInterface       $storage
    ) {
    }

    /**
     * @inheritDoc
     */
    public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
    {
        $authorization = $request->getHeader('Authorization');
        if (is_string($authorization)) {
            if (!preg_match($this->pattern, $authorization, $matches)) {
                return (new Response())->withBody(
                    new UnauthorizedProblemDetails($request)
                );
            }

            $identity = $this->authenticator->authenticate(new Header($matches['scheme'], $matches['credentials']));
            if (!$identity instanceof IdentityInterface) {
                return (new Response())->withBody(
                    new UnauthorizedProblemDetails($request)
                );
            }

            $this->storage->setIdentity($identity);
        }

        return $chain->proceed($request);
    }
}
