<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Hateoas\Framework\ServiceLocator\Loader;

use ExtendsSoftware\ExaPHP\Hateoas\Expander\Expander;
use ExtendsSoftware\ExaPHP\Hateoas\Expander\ExpanderInterface;
use ExtendsSoftware\ExaPHP\Hateoas\Framework\Http\Middleware\Hateoas\HateoasMiddleware;
use ExtendsSoftware\ExaPHP\Hateoas\Serializer\Json\JsonSerializer;
use ExtendsSoftware\ExaPHP\Hateoas\Serializer\SerializerInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\ReflectionResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class HateoasConfigLoaderTest extends TestCase
{
    /**
     * Test that loader will return config.
     *
     * @covers \ExtendsSoftware\ExaPHP\Hateoas\Framework\ServiceLocator\Loader\HateoasConfigLoader::load()
     */
    public function testLoad(): void
    {
        $loader = new HateoasConfigLoader();

        /** @noinspection PhpUnhandledExceptionInspection */
        $this->assertSame(
            [
                ServiceLocatorInterface::class => [
                    ReflectionResolver::class => [
                        SerializerInterface::class => JsonSerializer::class,
                        HateoasMiddleware::class => HateoasMiddleware::class,
                        ExpanderInterface::class => Expander::class,
                    ],
                ],
            ],
            $loader->load()
        );
    }
}
