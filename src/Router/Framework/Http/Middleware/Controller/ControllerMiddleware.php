<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Framework\Http\Middleware\Controller;

use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\MiddlewareInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Router\Controller\Executor\ExecutorException;
use ExtendsSoftware\ExaPHP\Router\Controller\Executor\ExecutorInterface;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatchInterface;

class ControllerMiddleware implements MiddlewareInterface
{
    /**
     * ControllerMiddleware constructor.
     *
     * @param ExecutorInterface $executor
     */
    public function __construct(private readonly ExecutorInterface $executor)
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
            $parameters = $match->getParameters();
            if (isset($parameters['controller'])) {
                return $this->executor->execute($request, $match);
            }
        }

        return $chain->proceed($request);
    }
}
