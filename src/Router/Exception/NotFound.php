<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Exception;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Router\RouterException;
use LogicException;

class NotFound extends LogicException implements RouterException
{
    /**
     * NotFound constructor.
     *
     * @param RequestInterface $request
     */
    public function __construct(private readonly RequestInterface $request)
    {
        parent::__construct('Request could not be matched by a route.');
    }

    /**
     * Get request.
     *
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
