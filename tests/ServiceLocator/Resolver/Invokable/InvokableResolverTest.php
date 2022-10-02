<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Invokable;

use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Invokable\Exception\NonExistingClass;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class InvokableResolverTest extends TestCase
{
    /**
     * Register.
     *
     * Test that an invokable can be registered.
     *
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Invokable\InvokableResolver::addInvokable()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Invokable\InvokableResolver::getService()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Invokable\InvokableResolver::hasService()
     */
    public function testRegister(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new InvokableResolver();
        $service = $resolver
            ->addInvokable('foo', Invokable::class)
            ->getService('foo', $serviceLocator, ['foo' => 'bar']);

        $this->assertInstanceOf(Invokable::class, $service);
    }

    /**
     * Has.
     *
     * Test that resolver can check for service existence.
     *
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Invokable\InvokableResolver::getService()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Invokable\InvokableResolver::hasService()
     */
    public function testHasService(): void
    {
        $resolver = new InvokableResolver();

        $this->assertFalse($resolver->hasService('foo'));
    }

    /**
     * Non-existing class.
     *
     * Test that a non-existing class can not be registered.
     *
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Invokable\InvokableResolver::addInvokable()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Invokable\InvokableResolver::getService()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Invokable\Exception\NonExistingClass::__construct()
     */
    public function testNonExistingClass(): void
    {
        $this->expectException(NonExistingClass::class);
        $this->expectExceptionMessage('Invokable "bar" must be a existing class.');

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new InvokableResolver();
        $resolver
            ->addInvokable('foo', 'bar')
            ->getService('foo', $serviceLocator);
    }

    /**
     * Create.
     *
     * Test that static factory will return resolver interface.
     *
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Invokable\InvokableResolver::factory()
     */
    public function testCreate(): void
    {
        $resolver = InvokableResolver::factory([
            'A' => Invokable::class,
        ]);

        $this->assertIsObject($resolver);
    }
}
