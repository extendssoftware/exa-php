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
use ReflectionNamedType;

use function is_string;

class ReflectionResolver implements ResolverInterface
{
    /**
     * An associative array which holds the definitions.
     *
     * @var mixed[]
     */
    private array $definitions = [];

    /**
     * @inheritDoc
     */
    public static function factory(array $services): ResolverInterface
    {
        $resolver = new ReflectionResolver();
        foreach ($services as $key => $definition) {
            if (!is_string($key) && is_string($definition)) {
                $key = $definition;
            }

            $resolver->addReflection($key, $definition);
        }

        return $resolver;
    }

    /**
     * @inheritDoc
     */
    public function hasService(string $key): bool
    {
        return isset($this->definitions[$key]);
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function getService(string $key, ServiceLocatorInterface $serviceLocator, array $extra = null): object
    {
        $definition = $this->definitions[$key];

        if ($definition instanceof Closure) {
            $reflection = new ReflectionFunction($definition);
            $arguments = $this->resolveParameters($reflection, $serviceLocator);

            return $reflection->invokeArgs($arguments);
        } else {
            $reflectionClass = new ReflectionClass($definition);
            $constructor = $reflectionClass->getConstructor();
            $arguments = [];

            if ($constructor !== null) {
                $arguments = $this->resolveParameters($constructor, $serviceLocator);
            }

            return $reflectionClass->newInstanceArgs($arguments);
        }
    }

    /**
     * Register definition for key.
     *
     * @param string         $key
     * @param string|Closure $definition
     *
     * @return ReflectionResolver
     */
    public function addReflection(string $key, string|Closure $definition): ReflectionResolver
    {
        $this->definitions[$key] = $definition;

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
