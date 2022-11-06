<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ProblemDetails\Framework\ServiceLocator\Loader;

use ExtendsSoftware\ExaPHP\ProblemDetails\Framework\Http\Middleware\ProblemDetailsMiddleware;
use ExtendsSoftware\ExaPHP\ProblemDetails\Serializer\Json\JsonSerializer;
use ExtendsSoftware\ExaPHP\ProblemDetails\Serializer\SerializerInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Invokable\InvokableResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\ReflectionResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Utility\Loader\LoaderInterface;

class ProblemDetailsConfigLoader implements LoaderInterface
{
    /**
     * @inheritDoc
     */
    public function load(): array
    {
        return [
            ServiceLocatorInterface::class => [
                ReflectionResolver::class => [
                    ProblemDetailsMiddleware::class => ProblemDetailsMiddleware::class,
                ],
                InvokableResolver::class => [
                    SerializerInterface::class => JsonSerializer::class,
                ],
            ],
        ];
    }
}
