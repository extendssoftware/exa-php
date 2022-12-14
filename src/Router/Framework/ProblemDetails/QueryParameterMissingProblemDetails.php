<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Framework\ProblemDetails;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\ProblemDetails\ProblemDetails;
use ExtendsSoftware\ExaPHP\Router\Route\Query\Exception\QueryParameterMissing;

class QueryParameterMissingProblemDetails extends ProblemDetails
{
    /**
     * QueryParameterMissingProblemDetails constructor.
     *
     * @param RequestInterface      $request
     * @param QueryParameterMissing $exception
     */
    public function __construct(RequestInterface $request, QueryParameterMissing $exception)
    {
        parent::__construct(
            '/problems/router/query-parameter-missing',
            'Query parameter missing',
            sprintf(
                'Query parameter "%s" is missing.',
                $exception->getParameter()
            ),
            400,
            $request->getUri()->toRelative(),
            [
                'parameter' => $exception->getParameter(),
            ]
        );
    }
}
