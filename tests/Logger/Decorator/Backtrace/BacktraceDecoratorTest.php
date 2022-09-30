<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Decorator\Backtrace;

use ExtendsSoftware\ExaPHP\Logger\Decorator\DecoratorInterface;
use ExtendsSoftware\ExaPHP\Logger\LogInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class BacktraceDecoratorTest extends TestCase
{
    /**
     * Decorate.
     *
     * Test that log meta data will be decorated with (at least) a file name from the debug backtrace and that a new
     * instance will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Logger\Decorator\Backtrace\BacktraceDecorator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Decorator\Backtrace\BacktraceDecorator::decorate()
     */
    public function testDecorate(): void
    {
        $log = $this->createMock(LogInterface::class);
        $log
            ->method('andMetaData')
            ->with('file');

        /**
         * @var LogInterface $log
         */
        $decorator = new BacktraceDecorator();

        $this->assertNotSame($log, $decorator->decorate($log));
    }

    /**
     * Factory.
     *
     * Test that create method will return a DecoratorInterface instance.
     *
     * @covers \ExtendsSoftware\ExaPHP\Logger\Decorator\Backtrace\BacktraceDecorator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Decorator\Backtrace\BacktraceDecorator::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $decorator = BacktraceDecorator::factory(BacktraceDecorator::class, $serviceLocator, [
            'limit' => 5,
        ]);

        $this->assertInstanceOf(DecoratorInterface::class, $decorator);
    }
}
