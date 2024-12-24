<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Exception;

use ExtendsSoftware\ExaPHP\Router\RouterException;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use InvalidArgumentException;

use function sprintf;

class InvalidQueryString extends InvalidArgumentException implements RouterException
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
                'Value for query string parameter "%s" is invalid.',
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
