<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Framework\Http\Middleware\Executor;

use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\MiddlewareInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Router\Executor\ExecutorException;
use ExtendsSoftware\ExaPHP\Router\Executor\ExecutorInterface;
use ExtendsSoftware\ExaPHP\Router\Route\Match\RouteMatchInterface;

readonly class ExecutorMiddleware implements MiddlewareInterface
{
    /**
     * ExecutorMiddleware constructor.
     *
     * @param ExecutorInterface $executor
     */
    public function __construct(private ExecutorInterface $executor)
    {
    }

    /**
     * @inheritDoc
     * @throws ExecutorException
     */
    public function process(RequestInterface $request, MiddlewareChainInterface $chain): ResponseInterface
    {
        $match = $request->getAttribute('routeMatch');
        if ($match instanceof RouteMatchInterface) {
            return $this->executor->execute($request, $match);
        }

        return $chain->proceed($request);
    }
}
