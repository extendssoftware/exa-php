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
     * @param string|null      $reference
     */
    public function __construct(RequestInterface $request, string $reference = null)
    {
        $additional = null;
        if (is_string($reference)) {
            $additional = [
                'reference' => $reference,
            ];
        }

        parent::__construct(
            '/problems/application/internal-server-error',
            'Internal Server Error',
            'An unknown error occurred.',
            500,
            $request->getUri()->toRelative(),
            $additional
        );
    }
}
