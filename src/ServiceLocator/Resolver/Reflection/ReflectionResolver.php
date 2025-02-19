<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection;

use Closure;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\Exception\InvalidParameter;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\ResolverInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorException;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Utility\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use ReflectionNamedType;

use function is_string;

class ReflectionResolver implements ResolverInterface
{
    /**
     * An associative array which holds the classes.
     *
     * @var class-string[]
     */
    private array $classes = [];

    /**
     * @inheritDoc
     */
    public static function factory(array $services): ResolverInterface
    {
        $resolver = new ReflectionResolver();
        foreach ($services as $key => $class) {
            if (!is_string($key)) {
                $key = $class;
            }

            $resolver->addReflection($key, $class);
        }

        return $resolver;
    }

    /**
     * @inheritDoc
     */
    public function hasService(string $key): bool
    {
        return isset($this->classes[$key]);
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function getService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        $class = $this->classes[$key];

        return $this->instantiateClass($class, $serviceLocator);
    }

    /**
     * Instantiate new class instance from class string.
     *
     * @param class-string            $class
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return object
     * @throws InvalidParameter
     * @throws ReflectionException
     * @throws ServiceLocatorException
     */
    public function instantiateClass(string $class, ServiceLocatorInterface $serviceLocator): object
    {
        $reflectionClass = new ReflectionClass($class);
        $arguments = [];

        $constructor = $reflectionClass->getConstructor();
        if ($constructor instanceof ReflectionMethod) {
            $arguments = $this->resolveParameters($constructor, $serviceLocator);
        }

        return $reflectionClass->newInstanceArgs($arguments);
    }

    /**
     * Get service from closure.
     *
     * @param Closure                 $closure
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return object
     * @throws InvalidParameter
     * @throws ReflectionException
     * @throws ServiceLocatorException
     */
    public function getServiceFromClosure(Closure $closure, ServiceLocatorInterface $serviceLocator): object
    {
        $reflection = new ReflectionFunction($closure);
        $arguments = $this->resolveParameters($reflection, $serviceLocator);

        return $reflection->invokeArgs($arguments);
    }

    /**
     * Register class for key.
     *
     * @param string       $key
     * @param class-string $class
     *
     * @return ReflectionResolver
     */
    public function addReflection(string $key, string $class): ReflectionResolver
    {
        $this->classes[$key] = $class;

        return $this;
    }

    /**
     * Resolve parameters for function.
     *
     * @param ReflectionFunctionAbstract $function
     * @param ServiceLocatorInterface    $serviceLocator
     *
     * @return array<object>
     * @throws InvalidParameter
     * @throws ServiceLocatorException
     */
    private function resolveParameters(
        ReflectionFunctionAbstract $function,
        ServiceLocatorInterface $serviceLocator,
    ): array {
        $arguments = [];
        foreach ($function->getParameters() as $parameter) {
            $type = $parameter->getType();
            if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
                throw new InvalidParameter($parameter);
            }

            $name = $type->getName();
            if ($name === ServiceLocatorInterface::class) {
                $arguments[] = $serviceLocator;
            } elseif ($name === ContainerInterface::class) {
                $arguments[] = $serviceLocator->getContainer();
            } else {
                $arguments[] = $serviceLocator->getService($name);
            }
        }

        return $arguments;
    }
}
