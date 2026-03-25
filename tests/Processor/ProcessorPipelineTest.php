<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor;

use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use PHPUnit\Framework\TestCase;

class ProcessorPipelineTest extends TestCase
{
    /**
     * Process.
     *
     * Asserts that internal transformers are invoked sequentially, each receiving the prior transformed value and
     * returning a new one.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\ProcessorPipeline::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\ProcessorPipeline::process()
     *
     * @return void
     */
    public function testProcess(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->expects($this->exactly(2))
            ->method('getValue')
            ->willReturnOnConsecutiveCalls('2', '3');

        $result
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $processor1 = $this->createMock(ProcessorInterface::class);
        $processor1
            ->expects($this->once())
            ->method('process')
            ->with('1', ['foo' => 'bar'])
            ->willReturn($result);

        $processor2 = $this->createMock(ProcessorInterface::class);
        $processor2
            ->expects($this->once())
            ->method('process')
            ->with('2', ['foo' => 'bar'])
            ->willReturn($result);

        $pipeline = new ProcessorPipeline([
            $processor1,
            $processor2,
        ]);
        $result = $pipeline->process('1', ['foo' => 'bar']);

        $this->assertSame('3', $result->getValue());
    }

    /**
     * Process with no processors.
     *
     * Asserts that the value is returned when no processors are given.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\ProcessorPipeline::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\ProcessorPipeline::process()
     *
     * @return void
     */
    public function testProcessWithNoProcessors(): void
    {
        $pipeline = new ProcessorPipeline([]);
        $result = $pipeline->process('test');

        $this->assertSame('test', $result->getValue());
        $this->assertTrue($result->isValid());
    }

    /**
     * Process with non-string.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\ProcessorPipeline::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\ProcessorPipeline::process()
     *
     * @return void
     */
    public function testProcessWithNonString(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->expects($this->once())
            ->method('getValue')
            ->willReturn(456);

        $processor = $this->createMock(ProcessorInterface::class);
        $processor
            ->expects($this->once())
            ->method('process')
            ->with(123, ['foo' => 'bar'])
            ->willReturn($result);

        $pipeline = new ProcessorPipeline([$processor]);
        $result = $pipeline->process(123, ['foo' => 'bar']);

        $this->assertSame(456, $result->getValue());
    }

    /**
     * Process will return on invalid result.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\ProcessorPipeline::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\ProcessorPipeline::process()
     *
     * @return void
     */
    public function testProcessWillReturnOnInvalidResult(): void
    {
        $result1 = $this->createMock(ResultInterface::class);
        $result1
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(false);

        $processor1 = $this->createMock(ProcessorInterface::class);
        $processor1
            ->expects($this->once())
            ->method('process')
            ->with('1', ['foo' => 'bar'])
            ->willReturn($result1);

        $processor2 = $this->createMock(ProcessorInterface::class);
        $processor2
            ->expects($this->never())
            ->method('process');

        $pipeline = new ProcessorPipeline([
            $processor1,
            $processor2,
        ]);
        $result = $pipeline->process('1', ['foo' => 'bar']);

        $this->assertSame($result, $result);
    }
}
