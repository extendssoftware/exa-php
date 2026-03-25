<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Other;

use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use PHPUnit\Framework\TestCase;

class ProxyProcessorTest extends TestCase
{
    /**
     * Process.
     *
     * Test that processor will act as a proxy to the inner processor.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\ProxyProcessor::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\ProxyProcessor::process()
     */
    public function testProcess(): void
    {
        $expectedResult = $this->createMock(ResultInterface::class);

        $processor = $this->createMock(ProcessorInterface::class);
        $processor
            ->expects($this->once())
            ->method('process')
            ->with('foo', ['bar'])
            ->willReturn($expectedResult);

        $optionalProcessor = new ProxyProcessor($processor);
        $actualResult = $optionalProcessor->process('foo', ['bar']);

        $this->assertSame($expectedResult, $actualResult);
    }
}
