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
use ExtendsSoftware\ExaPHP\Router\Route\Match\RouteMatchInterface;

readonly class AuthorizationMiddleware implements MiddlewareInterface
{
    /**
     * AuthorizationMiddleware constructor.
     *
     * @param AuthorizerInterface $authorizer
     */
    public function __construct(private AuthorizerInterface $authorizer)
    {
    }

    /**
     * @inheritDoc
     */
    public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
    {
        $match = $request->getAttribute('routeMatch');
        if ($match instanceof RouteMatchInterface) {
            $permission = $match->getParameter('permission');
            if ($permission) {
                $identity = $request->getAttribute('identity');
                if (!$this->authorizer->isPermitted(new Permission($permission), $identity)) {
                    return (new Response())->withBody(
                        new ForbiddenProblemDetails($request)
                    );
                }
            }
        }

        return $chain->proceed($request);
    }
}
