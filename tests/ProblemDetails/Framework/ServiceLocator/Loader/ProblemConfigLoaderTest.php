<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ProblemDetails\Framework\ServiceLocator\Loader;

use ExtendsSoftware\ExaPHP\ProblemDetails\Framework\Http\Middleware\ProblemDetailsMiddleware;
use ExtendsSoftware\ExaPHP\ProblemDetails\Serializer\Json\JsonSerializer;
use ExtendsSoftware\ExaPHP\ProblemDetails\Serializer\SerializerInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Invokable\InvokableResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\ReflectionResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class ProblemConfigLoaderTest extends TestCase
{
    /**
     * Load.
     *
     * Test that loader will return correct config.
     *
     * @covers \ExtendsSoftware\ExaPHP\ProblemDetails\Framework\ServiceLocator\Loader\ProblemDetailsConfigLoader::load()
     */
    public function testProcess(): void
    {
        $loader = new ProblemDetailsConfigLoader();
        $this->assertSame(
            [
                ServiceLocatorInterface::class => [
                    ReflectionResolver::class => [
                        ProblemDetailsMiddleware::class => ProblemDetailsMiddleware::class,
                    ],
                    InvokableResolver::class => [
                        SerializerInterface::class => JsonSerializer::class,
                    ],
                ],
            ],
            $loader->load()
        );
    }
}
