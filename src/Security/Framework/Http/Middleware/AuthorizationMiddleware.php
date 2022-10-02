<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Security\Framework\Http\Middleware;

use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\MiddlewareInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\Response;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatchInterface;
use ExtendsSoftware\ExaPHP\Security\Framework\ProblemDetails\ForbiddenProblemDetails;
use ExtendsSoftware\ExaPHP\Security\SecurityServiceInterface;

class AuthorizationMiddleware implements MiddlewareInterface
{
    /**
     * RoutePermissionMiddleware constructor.
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
        $match = $request->getAttribute('routeMatch');
        if ($match instanceof RouteMatchInterface) {
            $parameters = $match->getParameters();

            if (isset($parameters['permissions']) || isset($parameters['roles'])) {
                $authorized = false;

                foreach ($parameters['permissions'] ?? [] as $permission) {
                    if ($this->securityService->isPermitted($permission)) {
                        $authorized = true;
                    }
                }

                foreach ($parameters['roles'] ?? [] as $role) {
                    if ($this->securityService->hasRole($role)) {
                        $authorized = true;
                    }
                }

                if (!$authorized) {
                    return (new Response())->withBody(
                        new ForbiddenProblemDetails($request)
                    );
                }
            }
        }

        return $chain->proceed($request);
    }
}
