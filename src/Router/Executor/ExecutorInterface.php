<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Executor;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Router\Route\Match\RouteMatchInterface;

interface ExecutorInterface
{
    /**
     * Execute request and route match to controller and return response.
     *
     * @param RequestInterface    $request
     * @param RouteMatchInterface $routeMatch
     *
     * @return ResponseInterface
     */
    public function execute(RequestInterface $request, RouteMatchInterface $routeMatch): ResponseInterface;
}
