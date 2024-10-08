<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Framework\Http\Middleware\Logger;

use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\MiddlewareInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Logger\LoggerInterface;
use Throwable;

readonly class LoggerMiddleware implements MiddlewareInterface
{
    /**
     * LoggerMiddleware constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
    {
        try {
            return $chain->proceed($request);
        } catch (Throwable $throwable) {
            $this->logger->emerg($throwable->getMessage(), throwable: $throwable);

            throw $throwable;
        }
    }
}
