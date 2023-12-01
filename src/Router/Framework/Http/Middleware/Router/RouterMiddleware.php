<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Framework\Http\Middleware\Router;

use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\MiddlewareInterface;
use ExtendsSoftware\ExaPHP\Http\Request\Method\Method;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\Response;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Router\Exception\InvalidQueryString;
use ExtendsSoftware\ExaPHP\Router\Exception\InvalidRequestBody;
use ExtendsSoftware\ExaPHP\Router\Exception\MethodNotAllowed;
use ExtendsSoftware\ExaPHP\Router\Exception\NotFound;
use ExtendsSoftware\ExaPHP\Router\Exception\QueryParametersNotAllowed;
use ExtendsSoftware\ExaPHP\Router\Framework\ProblemDetails\InvalidQueryStringProblemDetails;
use ExtendsSoftware\ExaPHP\Router\Framework\ProblemDetails\InvalidRequestBodyProblemDetails;
use ExtendsSoftware\ExaPHP\Router\Framework\ProblemDetails\MethodNotAllowedProblemDetails;
use ExtendsSoftware\ExaPHP\Router\Framework\ProblemDetails\NotFoundProblemDetails;
use ExtendsSoftware\ExaPHP\Router\Framework\ProblemDetails\QueryParameterNotAllowedProblemDetails;
use ExtendsSoftware\ExaPHP\Router\RouterException;
use ExtendsSoftware\ExaPHP\Router\RouterInterface;

use function array_map;

readonly class RouterMiddleware implements MiddlewareInterface
{
    /**
     * Create a new router middleware.
     *
     * @param RouterInterface $router
     */
    public function __construct(private RouterInterface $router)
    {
    }

    /**
     * @inheritDoc
     * @throws RouterException
     */
    public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
    {
        try {
            $match = $this->router->route($request);
        } catch (MethodNotAllowed $exception) {
            return (new Response())
                ->withHeader(
                    'Allow',
                    implode(
                        ', ',
                        array_map(
                            fn(Method $method): string => $method->value,
                            $exception->getAllowedMethods()
                        )
                    )
                )
                ->withBody(
                    new MethodNotAllowedProblemDetails($request, $exception)
                );
        } catch (NotFound) {
            return (new Response())->withBody(
                new NotFoundProblemDetails($request)
            );
        } catch (InvalidQueryString $exception) {
            return (new Response())->withBody(
                new InvalidQueryStringProblemDetails($request, $exception)
            );
        } catch (QueryParametersNotAllowed $exception) {
            return (new Response())->withBody(
                new QueryParameterNotAllowedProblemDetails($request, $exception)
            );
        } catch (InvalidRequestBody $exception) {
            return (new Response())->withBody(
                new InvalidRequestBodyProblemDetails($request, $exception)
            );
        }

        return $chain->proceed(
            $request->andAttribute('routeMatch', $match)
        );
    }
}
