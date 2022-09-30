<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Framework\ProblemDetails;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\ProblemDetails\ProblemDetails;
use ExtendsSoftware\ExaPHP\Router\Route\Query\Exception\InvalidQueryString;

class InvalidQueryStringProblemDetails extends ProblemDetails
{
    /**
     * InvalidQueryStringProblemDetails constructor.
     *
     * @param RequestInterface   $request
     * @param InvalidQueryString $exception
     */
    public function __construct(RequestInterface $request, InvalidQueryString $exception)
    {
        parent::__construct(
            '/problems/router/invalid-query-string',
            'Invalid query string',
            sprintf('Value for query string parameter "%s" is invalid.', $exception->getParameter()),
            400,
            $request->getUri()->toRelative(),
            [
                'parameter' => $exception->getParameter(),
                'reason' => $exception->getResult(),
            ]
        );
    }
}
