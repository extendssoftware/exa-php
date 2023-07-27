<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application\Framework\ProblemDetails;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\ProblemDetails\ProblemDetails;

class InternalServerErrorProblemDetails extends ProblemDetails
{
    /**
     * InternalServerErrorProblemDetails constructor.
     *
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        parent::__construct(
            '/problems/application/internal-server-error',
            'Internal Server Error',
            'An unknown error occurred.',
            500,
            $request->getUri()->toRelative()
        );
    }
}
