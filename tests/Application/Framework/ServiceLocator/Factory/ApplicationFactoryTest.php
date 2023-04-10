<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application\Framework\ServiceLocator\Factory;

use ExtendsSoftware\ExaPHP\Application\ApplicationInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\Http\Request\RequestInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Shell\ShellInterface;
use PHPUnit\Framework\TestCase;

class ApplicationFactoryTest extends TestCase
{
    /**
     * Create HTTP application.
     *
     * Test that factory will create an instance of ApplicationInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Application\Framework\ServiceLocator\Factory\ApplicationFactory::createService()
     * @covers \ExtendsSoftware\ExaPHP\Application\Framework\ServiceLocator\Factory\ApplicationFactory::getHttpApplication()
     */
    public function testCreateHttpApplication(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->exactly(2))
            ->method('getService')
            ->willReturnCallback(fn($class) => match ([$class]) {
                [MiddlewareChainInterface::class] => $this->createMock(MiddlewareChainInterface::class),
                [RequestInterface::class] => $this->createMock(RequestInterface::class)
            })
        ;

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $factory = new ApplicationFactory();
        $application = $factory->createService(ApplicationInterface::class, $serviceLocator, [
            'modules' => [],
            'console' => false,
        ]);

        $this->assertInstanceOf(ApplicationInterface::class, $application);
    }

    /**
     * Create console application.
     *
     * Test that factory will create an instance of ApplicationInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Application\Framework\ServiceLocator\Factory\ApplicationFactory::createService()
     * @covers \ExtendsSoftware\ExaPHP\Application\Framework\ServiceLocator\Factory\ApplicationFactory::getConsoleApplication()
     */
    public function testCreateConsoleApplication(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('getService')
            ->with(ShellInterface::class)
            ->willReturn($this->createMock(ShellInterface::class));

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $factory = new ApplicationFactory();
        $application = $factory->createService(ApplicationInterface::class, $serviceLocator, [
            'modules' => [],
            'console' => true,
        ]);

        $this->assertInstanceOf(ApplicationInterface::class, $application);
    }
}
