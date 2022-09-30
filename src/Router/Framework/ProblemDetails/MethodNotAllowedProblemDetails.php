<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Framework\ProblemDetails;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\ProblemDetails\ProblemDetails;
use ExtendsSoftware\ExaPHP\Router\Route\Method\Exception\MethodNotAllowed;

class MethodNotAllowedProblemDetails extends ProblemDetails
{
    /**
     * MethodNotAllowedProblemDetails constructor.
     *
     * @param RequestInterface $request
     * @param MethodNotAllowed $exception
     */
    public function __construct(RequestInterface $request, MethodNotAllowed $exception)
    {
        parent::__construct(
            '/problems/router/method-not-allowed',
            'Method not allowed',
            sprintf('Method "%s" is not allowed.', $exception->getMethod()),
            405,
            $request->getUri()->toRelative(),
            [
                'method' => $exception->getMethod(),
                'allowed_methods' => $exception->getAllowedMethods(),
            ]
        );
    }
}
