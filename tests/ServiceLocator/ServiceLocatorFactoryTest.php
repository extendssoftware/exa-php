<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator;

use ExtendsSoftware\ExaPHP\ServiceLocator\Exception\UnknownResolverType;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Alias\AliasResolver;
use PHPUnit\Framework\TestCase;

class ServiceLocatorFactoryTest extends TestCase
{
    /**
     * Create.
     *
     * Test that a ServiceLocatorInterface will be created from a config.
     *
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorFactory::create()
     */
    public function testCreate(): void
    {
        $factory = new ServiceLocatorFactory();
        $serviceLocator = $factory->create([
            ServiceLocatorInterface::class => [
                AliasResolver::class => [
                    'foo' => 'bar',
                ],
            ],
        ]);

        $this->assertIsObject($serviceLocator);
    }

    /**
     * Unknown resolver.
     *
     * Test that an unknown resolver can not be found.
     *
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorFactory::create()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Exception\UnknownResolverType::__construct()
     */
    public function testUnknownResolver(): void
    {
        $this->expectException(UnknownResolverType::class);
        $this->expectExceptionMessage('Resolver must be instance or subclass of ResolverInterface, got "A".');

        $factory = new ServiceLocatorFactory();
        $factory->create([
            ServiceLocatorInterface::class => [
                'A' => [],
            ],
        ]);
    }
}
