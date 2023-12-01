<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection;

use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\Exception\InvalidParameter;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\ResolverInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Utility\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;

class ReflectionResolver implements ResolverInterface
{
    /**
     * An associative array which holds the classes.
     *
     * @var mixed[]
     */
    private array $classes = [];

    /**
     * @inheritDoc
     */
    public static function factory(array $services): ResolverInterface
    {
        $resolver = new ReflectionResolver();
        foreach ($services as $key => $class) {
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
        $constructor = (new ReflectionClass($class))->getConstructor();

        $values = [];
        if ($constructor instanceof ReflectionMethod) {
            foreach ($constructor->getParameters() as $parameter) {
                $type = $parameter->getType();
                if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
                    throw new InvalidParameter($parameter);
                }

                $name = $type->getName();
                if ($name === ServiceLocatorInterface::class) {
                    $values[] = $serviceLocator;
                } elseif ($name === ContainerInterface::class) {
                    $values[] = $serviceLocator->getContainer();
                } else {
                    $values[] = $serviceLocator->getService($name);
                }
            }
        }

        return new $class(...$values);
    }

    /**
     * Register class for key.
     *
     * @param string $key
     * @param string $class
     *
     * @return ReflectionResolver
     */
    public function addReflection(string $key, string $class): ReflectionResolver
    {
        $this->classes[$key] = $class;

        return $this;
    }
}
