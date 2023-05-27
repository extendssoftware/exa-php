<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection;

use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\Exception\InvalidParameter;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Utility\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;

class ReflectionResolverTest extends TestCase
{
    /**
     * Register.
     *
     * Test that an invokable can be registered.
     *
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\ReflectionResolver::addReflection()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\ReflectionResolver::getService()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\ReflectionResolver::hasService()
     */
    public function testRegister(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('getService')
            ->with(ReflectionB::class)
            ->willReturn(new ReflectionB());

        $serviceLocator
            ->expects($this->once())
            ->method('getContainer')
            ->willReturn($container);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new ReflectionResolver();
        $service = $resolver
            ->addReflection(ReflectionA::class, ReflectionA::class)
            ->getService(ReflectionA::class, $serviceLocator);

        $this->assertInstanceOf(ReflectionA::class, $service);
    }

    /**
     * Has.
     *
     * Test that resolver can check for service existence.
     *
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\ReflectionResolver::getService()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\ReflectionResolver::hasService()
     */
    public function testHasService(): void
    {
        $resolver = new ReflectionResolver();

        $this->assertFalse($resolver->hasService('foo'));
    }

    /**
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\ReflectionResolver::addReflection()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\ReflectionResolver::getService()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\ReflectionResolver::hasService()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\Exception\InvalidParameter::__construct()
     */
    public function testCanNotCreateClassWithNonObjectParameter(): void
    {
        $this->expectException(InvalidParameter::class);
        $this->expectExceptionMessage('Reflection parameter "name" must a named type argument and must be a class.');

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new ReflectionResolver();
        $resolver
            ->addReflection(ReflectionC::class, ReflectionC::class)
            ->getService(ReflectionC::class, $serviceLocator);
    }

    /**
     * Create.
     *
     * Test that static factory will return resolver interface.
     *
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\ReflectionResolver::factory()
     */
    public function testCreate(): void
    {
        $resolver = ReflectionResolver::factory([
            'A' => ReflectionA::class,
        ]);

        $this->assertIsObject($resolver);
    }
}
