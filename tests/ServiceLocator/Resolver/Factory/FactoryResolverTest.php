<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory;

use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\Exception\InvalidFactoryType;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\Exception\ServiceCreateFailed;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

class FactoryResolverTest extends TestCase
{
    /**
     * Register.
     *
     * Test that a new factory fqcn can be registered.
     *
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolver::addFactory()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolver::getService()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolver::hasService()
     */
    public function testRegister(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new FactoryResolver();
        $service = $resolver
            ->addFactory('foo', Factory::class)
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
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolver::getService()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolver::hasService()
     */
    public function testHasService(): void
    {
        $resolver = new FactoryResolver();

        $this->assertFalse($resolver->hasService('foo'));
    }

    /**
     * Register.
     *
     * Test that an invalid factory fqcn can not be registered.
     *
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolver::addFactory()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolver::getService()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\Exception\InvalidFactoryType::__construct()
     */
    public function testRegisterInvalidFqcn(): void
    {
        $this->expectException(InvalidFactoryType::class);
        $this->expectExceptionMessage('Factory must be a subclass of ServiceFactoryInterface, got "bar".');

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new FactoryResolver();
        $resolver
            ->addFactory('foo', 'bar')
            ->getService('foo', $serviceLocator);
    }

    /**
     * Service exception.
     *
     * Test that exception from factory can be caught.
     *
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolver::addFactory()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolver::getService()
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\Exception\ServiceCreateFailed::__construct()
     */
    public function testServiceException(): void
    {
        $this->expectException(ServiceCreateFailed::class);
        $this->expectExceptionMessage(
            'Failed to create service for key "foo". See previous exception for more details.'
        );

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $resolver = new FactoryResolver();
        $resolver
            ->addFactory('foo', FactoryFailed::class)
            ->getService('foo', $serviceLocator);
    }

    /**
     * Create.
     *
     * Test that static factory will return resolver interface.
     *
     * @covers \ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolver::factory()
     */
    public function testCreate(): void
    {
        $resolver = FactoryResolver::factory([
            'A' => Factory::class,
        ]);

        $this->assertIsObject($resolver);
    }
}
