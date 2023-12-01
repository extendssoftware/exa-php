<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Framework\ProblemDetails;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\ProblemDetails\ProblemDetails;

class NotFoundProblemDetails extends ProblemDetails
{
    /**
     * NotFoundProblemDetails constructor.
     *
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        parent::__construct(
            '/problems/router/not-found',
            'Not found',
            'Request could not be matched by a route.',
            404,
            $request->getUri()->toRelative()
        );
    }
}
