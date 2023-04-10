<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Router\Executor;

use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\Http\Response\ResponseInterface;
use ExtendsSoftware\ExaPHP\Router\Executor\Exception\ParameterNotFound;
use ExtendsSoftware\ExaPHP\Router\Route\Match\RouteMatchInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ReflectionException;
use ReflectionNamedType;
use function array_key_exists;

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
     * @throws ServiceLocatorException
     * @throws ReflectionException
     */
    public function execute(RequestInterface $request, RouteMatchInterface $routeMatch): ResponseInterface
    {
        $definition = $routeMatch->getDefinition();
        $reflectionClass = $definition->getReflectionClass();
        $reflectionMethod = $definition->getReflectionMethod();

        $key = $reflectionClass->getName();

        $arguments = [];
        $parameters = $routeMatch->getParameters();
        foreach ($reflectionMethod->getParameters() as $parameter) {
            $type = $parameter->getType();
            if ($type instanceof ReflectionNamedType) {
                switch ($type->getName()) {
                    case RequestInterface::class:
                        $arguments[] = $request;
                        continue 2;
                    case RouteMatchInterface::class:
                        $arguments[] = $routeMatch;
                        continue 2;
                }
            }

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

        return $reflectionMethod->invoke($this->serviceLocator->getService($key), ...$arguments);
    }
}
