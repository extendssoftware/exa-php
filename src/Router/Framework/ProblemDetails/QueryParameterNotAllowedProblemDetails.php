<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Framework\ProblemDetails;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\ProblemDetails\ProblemDetails;
use ExtendsSoftware\ExaPHP\Router\Exception\QueryParametersNotAllowed;
use function implode;
use function sprintf;

class QueryParameterNotAllowedProblemDetails extends ProblemDetails
{
    /**
     * QueryParameterNotAllowedProblemDetails constructor.
     *
     * @param RequestInterface          $request
     * @param QueryParametersNotAllowed $exception
     */
    public function __construct(RequestInterface $request, QueryParametersNotAllowed $exception)
    {
        parent::__construct(
            '/problems/router/query-parameter-not-allowed',
            'Query parameter not allowed',
            sprintf(
                'Query string parameters "%s" are not allowed.',
                implode(', ', $exception->getParameters())
            ),
            400,
            $request->getUri()->toRelative(),
            [
                'parameters' => $exception->getParameters(),
            ]
        );
    }
}
