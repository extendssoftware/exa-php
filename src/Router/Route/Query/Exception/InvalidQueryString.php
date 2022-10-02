<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route\Query\Exception;

use ExtendsSoftware\ExaPHP\Router\Route\Query\QueryRouteException;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use InvalidArgumentException;

class InvalidQueryString extends InvalidArgumentException implements QueryRouteException
{
    /**
     * InvalidParameterValue constructor.
     *
     * @param string          $parameter
     * @param ResultInterface $result
     */
    public function __construct(private readonly string $parameter, private readonly ResultInterface $result)
    {
        parent::__construct(
            sprintf(
                'Query string parameter "%s" failed to validate.',
                $parameter
            )
        );
    }

    /**
     * Get query string parameter.
     *
     * @return string
     */
    public function getParameter(): string
    {
        return $this->parameter;
    }

    /**
     * Get validation result.
     *
     * @return ResultInterface
     */
    public function getResult(): ResultInterface
    {
        return $this->result;
    }
}
