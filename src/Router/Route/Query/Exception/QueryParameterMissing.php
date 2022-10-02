<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route\Query\Exception;

use ExtendsSoftware\ExaPHP\Router\Route\RouteException;
use LogicException;

class QueryParameterMissing extends LogicException implements RouteException
{
    /**
     * RequiredParameterMissing constructor.
     *
     * @param string $parameter
     */
    public function __construct(private readonly string $parameter)
    {
        parent::__construct(
            sprintf(
                'Query string parameter "%s" value is required.',
                $parameter
            )
        );
    }

    /**
     * Get query parameter.
     *
     * @return string
     */
    public function getParameter(): string
    {
        return $this->parameter;
    }
}
