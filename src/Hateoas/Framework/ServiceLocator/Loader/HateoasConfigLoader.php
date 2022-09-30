<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Framework\ServiceLocator\Loader;

use ExtendsSoftware\ExaPHP\Hateoas\Expander\Expander;
use ExtendsSoftware\ExaPHP\Hateoas\Expander\ExpanderInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Framework\Http\Middleware\Hateoas\HateoasMiddleware;
use ExtendsSoftware\ExaPHP\Hateoas\Serializer\Json\JsonSerializer;
use ExtendsSoftware\ExaPHP\Hateoas\Serializer\SerializerInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Config\Loader\LoaderInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\ReflectionResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;

class HateoasConfigLoader implements LoaderInterface
{
    /**
     * @inheritDoc
     */
    public function load(): array
    {
        return [
            ServiceLocatorInterface::class => [
                ReflectionResolver::class => [
                    SerializerInterface::class => JsonSerializer::class,
                    HateoasMiddleware::class => HateoasMiddleware::class,
                    ExpanderInterface::class => Expander::class,
                ],
            ],
        ];
    }
}
