<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Other;

use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use PHPUnit\Framework\TestCase;

class CallbackProcessorTest extends TestCase
{
    /**
     * Process.
     *
     * Test that process will proxy to callback.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\CallbackProcessor::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\CallbackProcessor::process()
     */
    public function testValidate(): void
    {
        $expectedResult = $this->createMock(ResultInterface::class);

        $inner = $this->createMock(ProcessorInterface::class);
        $inner
            ->expects($this->once())
            ->method('process')
            ->with('foo', 'bar')
            ->willReturn($expectedResult);

        $processor = new CallbackProcessor(static function ($value, $context = null) use ($inner) {
            return $inner->process($value, $context);
        });
        $actualResult = $processor->process('foo', 'bar');

        $this->assertSame($expectedResult, $actualResult);
    }
}
