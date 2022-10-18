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
     * AuthorizationMiddleware constructor.
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
            $permissions = $match->getParameter('permissions');
            if (is_array($permissions)) {
                foreach ($permissions as $permission) {
                    if (!$this->securityService->isPermitted($permission)) {
                        return (new Response())->withBody(
                            new ForbiddenProblemDetails($request)
                        );
                    }
                }
            }
        }

        return $chain->proceed($request);
    }
}
