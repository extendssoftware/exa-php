<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Alias;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class AliasResolverTest extends TestCase
{
    /**
     * Register.
     *
     * Test that a new alias can be registered.
     *
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Alias\AliasResolver::addAlias()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Alias\AliasResolver::getService()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Alias\AliasResolver::hasService()
     */
    public function testRegister(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('getService')
            ->with('bar', ['foo' => 'bar'])
            ->willReturn(new stdClass());

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new AliasResolver();
        $service = $resolver
            ->addAlias('foo', 'bar')
            ->getService('foo', $serviceLocator, ['foo' => 'bar']);

        $this->assertInstanceOf(stdClass::class, $service);
    }

    /**
     * Has.
     *
     * Test that resolver can check for service existence.
     *
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Alias\AliasResolver::getService()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Alias\AliasResolver::hasService()
     */
    public function testHasService(): void
    {
        $resolver = new AliasResolver();

        $this->assertFalse($resolver->hasService('foo'));
    }

    /**
     * Create.
     *
     * Test that static factory will return resolver interface.
     *
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Alias\AliasResolver::factory()
     */
    public function testCreate(): void
    {
        $resolver = AliasResolver::factory([
            'A' => 'B',
        ]);

        $this->assertIsObject($resolver);
    }
}
