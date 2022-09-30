<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Security\Framework\ServiceLocator\Loader;

use ExtendsSoftware\ExaPHP\Security\Framework\Http\Middleware\AuthenticationMiddleware;
use ExtendsSoftware\ExaPHP\Security\Framework\Http\Middleware\AuthorizationMiddleware;
use ExtendsSoftware\ExaPHP\Security\SecurityService;
use ExtendsSoftware\ExaPHP\Security\SecurityServiceInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\ReflectionResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class SecurityConfigLoaderTest extends TestCase
{
    /**
     * Load.
     *
     * Test that loader returns correct array.
     *
     * @covers \ExtendsSoftware\ExaPHP\Security\Framework\ServiceLocator\Loader\SecurityConfigLoader::load()
     */
    public function testLoad(): void
    {
        $loader = new SecurityConfigLoader();

        $this->assertSame([
            ServiceLocatorInterface::class => [
                ReflectionResolver::class => [
                    AuthorizationMiddleware::class => AuthorizationMiddleware::class,
                    AuthenticationMiddleware::class => AuthenticationMiddleware::class,
                    SecurityServiceInterface::class => SecurityService::class,
                ],
            ],
        ], $loader->load());
    }
}
