<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Controller;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Router\Controller\Exception\ActionNotFound;
use ExtendsSoftware\ExaPHP\Router\Controller\Exception\ParameterNotFound;
use ExtendsSoftware\ExaPHP\Router\Route\RouteMatchInterface;
use ReflectionException;
use ReflectionMethod;

abstract class AbstractController implements ControllerInterface
{
    /**
     * String to append to the action.
     *
     * @var string
     */
    private string $postfix = 'Action';

    /**
     * Request.
     *
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * Route match.
     *
     * @var RouteMatchInterface
     */
    private RouteMatchInterface $routeMatch;

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function execute(RequestInterface $request, RouteMatchInterface $routeMatch): ResponseInterface
    {
        $this->request = $request;
        $this->routeMatch = $routeMatch;

        $action = $routeMatch->getParameter('action');
        if ($action === null) {
            throw new ActionNotFound();
        }

        $action = str_replace(['_', '-', '.',], ' ', strtolower($action));
        $action = lcfirst(str_replace(' ', '', ucwords($action)));
        $method = new ReflectionMethod($this, $action . $this->postfix);

        $parameters = $routeMatch->getParameters();
        $arguments = [];
        foreach ($method->getParameters() as $parameter) {
            $name = $parameter->getName();

            if (!array_key_exists($name, $parameters)) {
                if ($parameter->isDefaultValueAvailable()) {
                    $arguments[] = $parameter->getDefaultValue();
                } elseif ($parameter->allowsNull()) {
                    $arguments[] = null;
                } else {
                    throw new ParameterNotFound($name);
                }
            } else {
                $arguments[] = $parameters[$name];
            }
        }

        return $method->invokeArgs($this, $arguments);
    }

    /**
     * Get request.
     *
     * @return RequestInterface
     */
    protected function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * Get route match.
     *
     * @return RouteMatchInterface
     */
    protected function getRouteMatch(): RouteMatchInterface
    {
        return $this->routeMatch;
    }
}
