<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Controller;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatchInterface;

interface ControllerInterface
{
    /**
     * Execute controller with request and route match.
     *
     * Method must return result as an array. When there is no result to result, this method must return an empty
     * array. When no method can be found, an exception will be thrown.
     *
     * @param RequestInterface    $request
     * @param RouteMatchInterface $routeMatch
     *
     * @return ResponseInterface
     * @throws ControllerException
     */
    public function execute(RequestInterface $request, RouteMatchInterface $routeMatch): ResponseInterface;
}
