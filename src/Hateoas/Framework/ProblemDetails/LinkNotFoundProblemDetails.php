<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Framework\ProblemDetails;

use ExtendsSoftware\ExaPHP\Hateoas\Builder\Exception\LinkNotFound;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\ProblemDetails\ProblemDetails;

use function sprintf;

class LinkNotFoundProblemDetails extends ProblemDetails
{
    /**
     * LinkNotfoundProblemDetails constructor.
     *
     * @param RequestInterface $request
     * @param LinkNotFound $exception
     */
    public function __construct(RequestInterface $request, LinkNotFound $exception)
    {
        parent::__construct(
            '/problems/hateoas/link-not-found',
            'Link not found',
            'Link with rel can not be found.',
            404,
            $request->getUri()->toRelative(),
            [
                'rel' => $exception->getRel(),
            ]
        );
    }
}
