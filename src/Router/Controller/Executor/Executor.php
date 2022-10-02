<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Controller\Executor;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Router\Controller\ControllerException;
use ExtendsSoftware\ExaPHP\Router\Controller\ControllerInterface;
use ExtendsSoftware\ExaPHP\Router\Controller\Executor\Exception\ControllerExecutionFailed;
use ExtendsSoftware\ExaPHP\Router\Controller\Executor\Exception\ControllerNotFound;
use ExtendsSoftware\ExaPHP\Router\Controller\Executor\Exception\ControllerParameterMissing;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatchInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class Executor implements ExecutorInterface
{
    /**
     * Executor constructor.
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(private readonly ServiceLocatorInterface $serviceLocator)
    {
    }

    /**
     * @inheritDoc
     */
    public function execute(RequestInterface $request, RouteMatchInterface $routeMatch): ResponseInterface
    {
        $parameters = $routeMatch->getParameters();
        if (!isset($parameters['controller'])) {
            throw new ControllerParameterMissing();
        }

        $key = $parameters['controller'];
        try {
            /** @var ControllerInterface $controller */
            $controller = $this->serviceLocator->getService($key);
        } catch (ServiceLocatorException $exception) {
            throw new ControllerNotFound($key, $exception);
        }

        try {
            return $controller->execute($request, $routeMatch);
        } catch (ControllerException $exception) {
            throw new ControllerExecutionFailed($exception);
        }
    }
}
