<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Other;

use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use PHPUnit\Framework\TestCase;

class InterruptProcessorTest extends TestCase
{
    /**
     * Process.
     *
     * Test that get method will return correct values and validate calling inner processor process method.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\InterruptProcessor::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\InterruptProcessor::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\InterruptProcessor::mustInterrupt()
     */
    public function testProcess(): void
    {
        $innerResult = $this->createMock(ResultInterface::class);

        $innerProcessor = $this->createMock(ProcessorInterface::class);
        $innerProcessor
            ->expects($this->once())
            ->method('process')
            ->with('foo', 'bar')
            ->willReturn($innerResult);

        $processor = new InterruptProcessor($innerProcessor, true);
        $result = $processor->process('foo', 'bar');

        $this->assertTrue($processor->mustInterrupt());
        $this->assertSame($result, $result);
    }
}
