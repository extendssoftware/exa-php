<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Framework\ProblemDetails;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\ProblemDetails\ProblemDetails;
use ExtendsSoftware\ExaPHP\Router\Route\Method\Exception\InvalidRequestBody;

class InvalidRequestBodyProblemDetails extends ProblemDetails
{
    /**
     * InvalidRequestBodyProblemDetails constructor.
     *
     * @param RequestInterface   $request
     * @param InvalidRequestBody $exception
     */
    public function __construct(RequestInterface $request, InvalidRequestBody $exception)
    {
        parent::__construct(
            '/problems/router/invalid-request-body',
            'Invalid request body',
            'Request body is invalid.',
            400,
            $request->getUri()->toRelative(),
            [
                'errors' => $exception->getResult(),
            ]
        );
    }
}
