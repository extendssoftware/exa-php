<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Authorization\Framework\ServiceLocator\Loader;

use ExtendsSoftware\ExaPHP\Authorization\AuthorizerInterface;
use ExtendsSoftware\ExaPHP\Authorization\Framework\Http\Middleware\AuthorizationMiddleware;
use ExtendsSoftware\ExaPHP\Authorization\Framework\ServiceLocator\Factory\AuthorizerFactory;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\ReflectionResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class AuthorizationConfigLoaderTest extends TestCase
{
    /**
     * Load.
     *
     * Test that loader returns correct array.
     *
     * @covers \ExtendsSoftware\ExaPHP\Authorization\Framework\ServiceLocator\Loader\AuthorizationConfigLoader::load()
     */
    public function testLoad(): void
    {
        $loader = new AuthorizationConfigLoader();

        $this->assertSame([
            ServiceLocatorInterface::class => [
                FactoryResolver::class => [
                    AuthorizerInterface::class => AuthorizerFactory::class,
                ],
                ReflectionResolver::class => [
                    AuthorizationMiddleware::class => AuthorizationMiddleware::class,
                ],
            ],
            AuthorizerInterface::class => [
                'realms' => [],
            ],
        ], $loader->load());
    }
}
