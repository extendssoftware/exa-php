<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger;

use ExtendsSoftware\ExaPHP\Logger\Decorator\DecoratorInterface;
use ExtendsSoftware\ExaPHP\Logger\Priority\Alert\AlertPriority;
use ExtendsSoftware\ExaPHP\Logger\Priority\Critical\CriticalPriority;
use ExtendsSoftware\ExaPHP\Logger\Priority\Emergency\EmergencyPriority;
use ExtendsSoftware\ExaPHP\Logger\Priority\Error\ErrorPriority;
use ExtendsSoftware\ExaPHP\Logger\Priority\Informational\InformationalPriority;
use ExtendsSoftware\ExaPHP\Logger\Priority\Notice\NoticePriority;
use ExtendsSoftware\ExaPHP\Logger\Priority\PriorityInterface;
use ExtendsSoftware\ExaPHP\Logger\Priority\Warning\WarningPriority;
use ExtendsSoftware\ExaPHP\Logger\Writer\WriterInterface;
use PHPUnit\Framework\TestCase;
use Throwable;

class LoggerTest extends TestCase
{
    /**
     * /**
     *  Data provider for log priority and method.
     *
     * @return array[]
     */
    public static function priorityProvider(): array
    {
        return [
            [EmergencyPriority::class, 'emerg'],
            [AlertPriority::class, 'alert'],
            [CriticalPriority::class, 'crit'],
            [ErrorPriority::class, 'error'],
            [WarningPriority::class, 'warning'],
            [NoticePriority::class, 'notice'],
            [InformationalPriority::class, 'info'],
        ];
    }

    /**
     * Log.
     *
     * Test that message will be logged with priority and metadata.
     *
     * @covers \ExtendsSoftware\ExaPHP\Logger\Logger::addWriter()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Logger::addDecorator()
     * @covers \ExtendsSoftware\ExaPHP\Logger\LoggerWriter::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Logger\LoggerWriter::getWriter()
     * @covers \ExtendsSoftware\ExaPHP\Logger\LoggerWriter::mustInterrupt()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Logger::log()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Logger::decorate()
     */
    public function testLog(): void
    {
        $priority = $this->createMock(PriorityInterface::class);
        $throwable = $this->createMock(Throwable::class);

        $decorator = $this->createMock(DecoratorInterface::class);
        $decorator
            ->expects($this->once())
            ->method('decorate')
            ->willReturnCallback(function (LogInterface $log) {
                return $log->andMetaData('baz', 'qux');
            });

        $writer = $this->createMock(WriterInterface::class);
        $writer
            ->expects($this->once())
            ->method('write')
            ->with(
                $this->callback(function (LogInterface $log) use ($priority, $throwable) {
                    $this->assertSame('Error!', $log->getMessage());
                    $this->assertSame($priority, $log->getPriority());
                    $this->assertSame($throwable, $log->getThrowable());
                    $this->assertSame(['foo' => 'bar', 'baz' => 'qux'], $log->getMetaData());

                    return true;
                })
            );

        /**
         * @var WriterInterface   $writer
         * @var PriorityInterface $priority
         * @var Throwable         $throwable
         */
        $logger = new Logger();
        $logger
            ->addWriter($writer)
            ->addDecorator($decorator)
            ->log('Error!', $priority, ['foo' => 'bar'], $throwable);
    }

    /**
     * Priority methods.
     *
     * Test that message will be logged with predefined priority and metadata.
     *
     * @dataProvider priorityProvider()
     * @covers       \ExtendsSoftware\ExaPHP\Logger\Logger::addWriter()
     * @covers       \ExtendsSoftware\ExaPHP\Logger\LoggerWriter::__construct()
     * @covers       \ExtendsSoftware\ExaPHP\Logger\LoggerWriter::getWriter()
     * @covers       \ExtendsSoftware\ExaPHP\Logger\LoggerWriter::mustInterrupt()
     * @covers       \ExtendsSoftware\ExaPHP\Logger\Logger::log()
     * @covers       \ExtendsSoftware\ExaPHP\Logger\Logger::emerg()
     * @covers       \ExtendsSoftware\ExaPHP\Logger\Logger::alert()
     * @covers       \ExtendsSoftware\ExaPHP\Logger\Logger::crit()
     * @covers       \ExtendsSoftware\ExaPHP\Logger\Logger::error()
     * @covers       \ExtendsSoftware\ExaPHP\Logger\Logger::warning()
     * @covers       \ExtendsSoftware\ExaPHP\Logger\Logger::notice()
     * @covers       \ExtendsSoftware\ExaPHP\Logger\Logger::info()
     */
    public function testPriorityMethods(string $subclass, string $method): void
    {
        $throwable = $this->createMock(Throwable::class);

        $writer = $this->createMock(WriterInterface::class);
        $writer
            ->expects($this->once())
            ->method('write')
            ->with(
                $this->callback(function (LogInterface $log) use ($subclass, $throwable) {
                    $this->assertSame('Message!', $log->getMessage());
                    $this->assertInstanceOf($subclass, $log->getPriority());
                    $this->assertSame($throwable, $log->getThrowable());
                    $this->assertSame(['foo' => 'bar'], $log->getMetaData());

                    return true;
                })
            );

        /**
         * @var WriterInterface   $writer
         * @var PriorityInterface $priority
         * @var Throwable         $throwable
         */
        $logger = new Logger();
        $logger->addWriter($writer);

        $result = call_user_func_array([$logger, $method], ['Message!', ['foo' => 'bar'], $throwable]);
        $this->assertInstanceOf(LoggerInterface::class, $result);
    }

    /**
     * Interrupt.
     *
     * Test that writer will interrupt next writers.
     *
     * @covers \ExtendsSoftware\ExaPHP\Logger\Logger::addWriter()
     * @covers \ExtendsSoftware\ExaPHP\Logger\LoggerWriter::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Logger\LoggerWriter::getWriter()
     * @covers \ExtendsSoftware\ExaPHP\Logger\LoggerWriter::mustInterrupt()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Logger::log()
     */
    public function testInterrupt(): void
    {
        $priority = $this->createMock(PriorityInterface::class);
        $throwable = $this->createMock(Throwable::class);

        $writer = $this->createMock(WriterInterface::class);
        $writer
            ->expects($this->once())
            ->method('write');

        /**
         * @var WriterInterface   $writer
         * @var PriorityInterface $priority
         * @var Throwable         $throwable
         */
        $logger = new Logger();
        $logger
            ->addWriter($writer, true)
            ->addWriter($writer)
            ->addWriter($writer)
            ->log('Error!', $priority, ['foo' => 'bar'], $throwable);
    }
}
