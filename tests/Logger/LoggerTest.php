<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger;

use ExtendsSoftware\ExaPHP\Logger\Decorator\DecoratorInterface;
use ExtendsSoftware\ExaPHP\Logger\Priority\PriorityInterface;
use ExtendsSoftware\ExaPHP\Logger\Writer\WriterInterface;
use PHPUnit\Framework\TestCase;
use Throwable;

class LoggerTest extends TestCase
{
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
