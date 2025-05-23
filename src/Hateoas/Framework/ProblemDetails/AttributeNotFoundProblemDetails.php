<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Framework\ProblemDetails;

use ExtendsSoftware\ExaPHP\Hateoas\Builder\Exception\AttributeNotFound;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\ProblemDetails\ProblemDetails;

use function sprintf;

class AttributeNotFoundProblemDetails extends ProblemDetails
{
    /**
     * AttributeNotfoundProblemDetails constructor.
     *
     * @param RequestInterface $request
     * @param AttributeNotFound $exception
     */
    public function __construct(RequestInterface $request, AttributeNotFound $exception)
    {
        parent::__construct(
            '/problems/hateoas/attribute-not-found',
            'Attribute not found',
            'Attribute with property can not be found.',
            404,
            $request->getUri()->toRelative(),
            [
                'property' => $exception->getProperty(),
            ]
        );
    }
}
