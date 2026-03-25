<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Exception;

use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Router\RouterException;
use RuntimeException;

class InvalidRequestBody extends RuntimeException implements RouterException
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
     * Get processing result.
     *
     * @return ResultInterface
     */
    public function getResult(): ResultInterface
    {
        return $this->result;
    }
}
