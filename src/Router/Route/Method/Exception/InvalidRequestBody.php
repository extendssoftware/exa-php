<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Route\Method\Exception;

use ExtendsSoftware\ExaPHP\Router\Route\Method\MethodRouteException;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use RuntimeException;

class InvalidRequestBody extends RuntimeException implements MethodRouteException
{
    /**
     * RequestBodyInvalid constructor.
     *
     * @param ResultInterface $result
     */
    public function __construct(private readonly ResultInterface $result)
    {
        parent::__construct('Request body is invalid.');
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
