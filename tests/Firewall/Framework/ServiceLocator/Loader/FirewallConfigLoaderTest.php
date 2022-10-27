<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Firewall\Framework\ServiceLocator\Loader;

use ExtendsSoftware\ExaPHP\Firewall\FirewallInterface;
use ExtendsSoftware\ExaPHP\Firewall\Framework\Http\Middleware\FirewallMiddleware;
use ExtendsSoftware\ExaPHP\Firewall\Framework\ServiceLocator\Factory\FirewallFactory;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\ReflectionResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class FirewallConfigLoaderTest extends TestCase
{
    /**
     * Load.
     *
     * Test that loader returns correct array.
     *
     * @covers \ExtendsSoftware\ExaPHP\Firewall\Framework\ServiceLocator\Loader\FirewallConfigLoader::load()
     */
    public function testLoad(): void
    {
        $loader = new FirewallConfigLoader();

        $this->assertSame([
            ServiceLocatorInterface::class => [
                FactoryResolver::class => [
                    FirewallInterface::class => FirewallFactory::class,
                ],
                ReflectionResolver::class => [
                    FirewallMiddleware::class => FirewallMiddleware::class,
                ],
            ],
            FirewallInterface::class => [
                'realms' => [],
            ],
        ], $loader->load());
    }
}
