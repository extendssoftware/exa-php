<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authorization\Framework\Http\Middleware;

use ExtendsSoftware\ExaPHP\Authorization\AuthorizerInterface;
use ExtendsSoftware\ExaPHP\Authorization\Framework\ProblemDetails\ForbiddenProblemDetails;
use ExtendsSoftware\ExaPHP\Authorization\Permission\Permission;
use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\MiddlewareInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\Response;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Identity\Storage\Exception\IdentityNotSet;
use ExtendsSoftware\ExaPHP\Identity\Storage\StorageInterface;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatchInterface;

class AuthorizationMiddleware implements MiddlewareInterface
{
    /**
     * AuthorizationMiddleware constructor.
     *
     * @param AuthorizerInterface $authorizer
     * @param StorageInterface    $storage
     */
    public function __construct(
        private readonly AuthorizerInterface $authorizer,
        private readonly StorageInterface    $storage
    ) {
    }

    /**
     * @inheritDoc
     * @throws IdentityNotSet
     */
    public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
    {
        $match = $request->getAttribute('routeMatch');
        if ($match instanceof RouteMatchInterface) {
            $permission = new Permission($match->getName());
            if (!$this->authorizer->isPermitted($permission, $this->storage->getIdentity())) {
                return (new Response())->withBody(
                    new ForbiddenProblemDetails($request)
                );
            }
        }

        return $chain->proceed($request);
    }
}
