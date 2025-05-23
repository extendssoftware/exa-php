<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator;

use ExtendsSoftware\ExaPHP\ServiceLocator\Exception\ResolverNotFound;
use ExtendsSoftware\ExaPHP\ServiceLocator\Exception\ServiceNotFound;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Alias\AliasResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\ResolverInterface;
use ExtendsSoftware\ExaPHP\Utility\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class ServiceLocatorTest extends TestCase
{
    /**
     * Register.
     *
     * Test that a resolver can be registered.
     *
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocator::addResolver()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocator::getService()
     */
    public function testRegister(): void
    {
        $resolver = $this->createMock(ResolverInterface::class);
        $resolver
            ->expects($this->once())
            ->method('getService')
            ->with('A')
            ->willReturn(new stdClass());

        $resolver
            ->expects($this->once())
            ->method('hasService')
            ->with('A')
            ->willReturn(true);

        $container = $this->createMock(ContainerInterface::class);

        /**
         * @var ResolverInterface  $resolver
         * @var ContainerInterface $container
         */
        $serviceLocator = new ServiceLocator($container);
        $service = $serviceLocator
            ->addResolver($resolver, 'invokables')
            ->getService('A');

        $this->assertInstanceOf(stdClass::class, $service);
    }

    /**
     * Shared service.
     *
     * Test that a shared service will be returned and cached by the service locator.
     *
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocator::addResolver()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocator::getService()
     */
    public function testSharedService(): void
    {
        /**
         * @var ResolverInterface  $resolver
         * @var ContainerInterface $container
         */
        $container = $this->createMock(ContainerInterface::class);
        $serviceLocator = new ServiceLocator($container);

        $resolver = $this->createMock(ResolverInterface::class);
        $resolver
            ->expects($this->exactly(3))
            ->method('getService')
            ->willReturnCallback(fn($key, $instance, $extra) => match ([$key, $instance, $extra]) {
                ['A', $serviceLocator, null],
                ['A', $serviceLocator, ['foo' => 'bar']] => new stdClass(),
            });

        $resolver
            ->expects($this->exactly(3))
            ->method('hasService')
            ->with('A')
            ->willReturn(true);

        $service1 = $serviceLocator
            ->addResolver($resolver, 'invokables')
            ->getService('A', ['foo' => 'bar']);

        $service2 = $serviceLocator
            ->addResolver($resolver, 'invokables')
            ->getService('A');

        $service3 = $serviceLocator
            ->addResolver($resolver, 'invokables')
            ->getService('A');

        $service4 = $serviceLocator
            ->addResolver($resolver, 'invokables')
            ->getService('A', ['foo' => 'bar']);

        $this->assertNotSame($service1, $service2);
        $this->assertSame($service2, $service3);
        $this->assertNotSame($service3, $service4);
    }

    /**
     * Managed service.
     *
     * Test that a managed service will be returned and not cached by the service locator.
     *
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocator::addResolver()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocator::getService()
     */
    public function testManagedService(): void
    {
        $resolver = $this->createMock(ResolverInterface::class);
        $resolver
            ->expects($this->exactly(2))
            ->method('getService')
            ->with(
                'A',
                $this->isInstanceOf(ServiceLocatorInterface::class),
                ['foo' => 'bar']
            )
            ->willReturnOnConsecutiveCalls(
                new stdClass(),
                new stdClass()
            );

        $resolver
            ->expects($this->exactly(2))
            ->method('hasService')
            ->with('A')
            ->willReturn(true);

        $container = $this->createMock(ContainerInterface::class);

        /**
         * @var ResolverInterface  $resolver
         * @var ContainerInterface $container
         */
        $serviceLocator = new ServiceLocator($container);
        $service1 = $serviceLocator
            ->addResolver($resolver, 'invokables')
            ->getService('A', ['foo' => 'bar']);

        $service2 = $serviceLocator
            ->addResolver($resolver, 'invokables')
            ->getService('A', ['foo' => 'bar']);

        $this->assertNotSame($service1, $service2);
    }

    /**
     * Get resolver.
     *
     * Test that a resolver will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocator::addResolver()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocator::getResolver()
     */
    public function testGetResolver(): void
    {
        $resolver = $this->createMock(ResolverInterface::class);

        $container = $this->createMock(ContainerInterface::class);

        /**
         * @var ResolverInterface  $resolver
         * @var ContainerInterface $container
         */
        $serviceLocator = new ServiceLocator($container);
        $serviceLocator->addResolver($resolver, AliasResolver::class);

        $this->assertSame($resolver, $serviceLocator->getResolver(AliasResolver::class));
    }

    /**
     * Get config.
     *
     * Test that config is returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocator::getContainer()
     */
    public function testGetConfig(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        /**
         * @var ContainerInterface $container
         */
        $serviceLocator = new ServiceLocator($container);

        $this->assertSame($container, $serviceLocator->getContainer());
    }

    /**
     * Is console.
     *
     * Test that console is the current environment.
     *
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocator::isConsole()
     */
    public function testIsConsole(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        /**
         * @var ContainerInterface $container
         */
        $serviceLocator = new ServiceLocator($container);

        $this->assertTrue($serviceLocator->isConsole());
    }

    /**
     * Service not found.
     *
     * Test that a service can not be located and an exception will be thrown.
     *
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocator::getService()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Exception\ServiceNotFound::__construct()
     */
    public function testServiceNotFound(): void
    {
        $this->expectException(ServiceNotFound::class);
        $this->expectExceptionMessage('No service found for key "foo".');

        $container = $this->createMock(ContainerInterface::class);

        /**
         * @var ContainerInterface $container
         */
        $serviceLocator = new ServiceLocator($container);
        $serviceLocator->getService('foo');
    }

    /**
     * Resolver not found.
     *
     * Test that a resolver can not be found and an exception will be thrown.
     *
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocator::getResolver()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Exception\ResolverNotFound::__construct()
     */
    public function testResolverNotFound(): void
    {
        $this->expectException(ResolverNotFound::class);
        $this->expectExceptionMessage('No resolver found for key "foo".');

        $container = $this->createMock(ContainerInterface::class);

        /**
         * @var ContainerInterface $container
         */
        $serviceLocator = new ServiceLocator($container);
        $serviceLocator->getResolver('foo');
    }

    /**
     * Service locator as shared service.
     *
     * Test that service locator is available as a shared service.
     *
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocator::getService()
     */
    public function testServiceLocatorAsSharedService(): void
    {
        $container = $this->createMock(ContainerInterface::class);

        /**
         * @var ContainerInterface $container
         */
        $serviceLocator = new ServiceLocator($container);

        $this->assertSame($serviceLocator, $serviceLocator->getService(ServiceLocatorInterface::class));
    }
}
