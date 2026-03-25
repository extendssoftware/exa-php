<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Other;

use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class ValidProcessorTest extends TestCase
{
    /**
     * Process.
     *
     * Test that any value is valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\ValidProcessor::process()
     */
    public function testValid(): void
    {
        $processor = new ValidProcessor();

        $result1 = $processor->process('foo');
        $this->assertInstanceOf(ValidResult::class, $result1);
        $this->assertSame('foo', $result1->getValue());

        $result2 = $processor->process(9);
        $this->assertInstanceOf(ValidResult::class, $result2);
        $this->assertSame(9, $result2->getValue());

        $result3 = $processor->process(false);
        $this->assertInstanceOf(ValidResult::class, $result3);
        $this->assertSame(false, $result3->getValue());
    }
}
