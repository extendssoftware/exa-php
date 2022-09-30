<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Closure;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class ClosureResolverTest extends TestCase
{
    /**
     * Register.
     *
     * Test that a new closure can be registered.
     *
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Closure\ClosureResolver::addClosure()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Closure\ClosureResolver::getService()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Closure\ClosureResolver::hasService()
     */
    public function testRegister(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new ClosureResolver();
        $service = $resolver
            ->addClosure(
                'foo',
                static function (string $key, ServiceLocatorInterface $serviceLocator, array $extra = null) {
                    $service = new stdClass();
                    $service->key = $key;
                    $service->serviceLocator = $serviceLocator;
                    $service->extra = $extra;

                    return $service;
                }
            )
            ->getService('foo', $serviceLocator, ['foo' => 'bar']);

        $this->assertInstanceOf(stdClass::class, $service);
        $this->assertSame('foo', $service->key);
        $this->assertSame($serviceLocator, $service->serviceLocator);
        $this->assertSame(['foo' => 'bar'], $service->extra);
    }

    /**
     * Has.
     *
     * Test that resolver can check for service existence.
     *
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Closure\ClosureResolver::getService()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Closure\ClosureResolver::hasService()
     */
    public function testHasService(): void
    {
        $resolver = new ClosureResolver();

        $this->assertFalse($resolver->hasService('foo'));
    }

    /**
     * Create.
     *
     * Test that static factory will return resolver interface.
     *
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Closure\ClosureResolver::factory()
     */
    public function testCreate(): void
    {
        $resolver = ClosureResolver::factory([
            'A' => static function () {
            },
        ]);

        $this->assertIsObject($resolver);
    }
}
