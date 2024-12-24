<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Exception;

use ExtendsSoftware\ExaPHP\Router\Route\RouteException;
use LogicException;

use function implode;
use function sprintf;

class QueryParametersNotAllowed extends LogicException implements RouteException
{
    /**
     * RequiredParametersNotAllowed constructor.
     *
     * @param array<string> $parameters
     */
    public function __construct(private readonly array $parameters)
    {
        parent::__construct(
            sprintf(
                'Query string parameters "%s" are not allowed.',
                implode(', ', $parameters)
            )
        );
    }

    /**
     * Get query parameters.
     *
     * @return array<string>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
