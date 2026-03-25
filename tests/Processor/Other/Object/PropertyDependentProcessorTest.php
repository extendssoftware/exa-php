<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Other\Object;

use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class PropertyDependentProcessorTest extends TestCase
{
    /**
     * Data provider for is valid.
     *
     * @return array[]
     */
    public static function isValidProvider(): array
    {
        return [
            ['foo'],
            [true],
            [null],
            [2],
        ];
    }

    /**
     * Valid.
     *
     * Test that an object is valid.
     *
     * @dataProvider isValidProvider()
     * @covers       \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyDependentProcessor::__construct()
     * @covers       \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyDependentProcessor::addProperty()
     * @covers       \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyDependentProcessor::process()
     * @covers       \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyDependentProcessor::findProperty()
     */
    public function testIsValid(mixed $value): void
    {
        $innerResult = $this->createMock(ResultInterface::class);
        $innerResult
            ->method('isValid')
            ->willReturn(true);

        $innerProcessor1 = $this->createMock(ProcessorInterface::class);
        $innerProcessor1
            ->expects($this->once())
            ->method('process')
            ->with('qux')
            ->willReturn($innerResult);

        $innerProcessor2 = $this->createMock(ProcessorInterface::class);
        $innerProcessor2
            ->expects($this->never())
            ->method('process');

        $processor = new PropertyDependentProcessor('foo', [
            [$value, $innerProcessor1],
            ['baz', $innerProcessor2],
        ]);
        $result = $processor->process(
            'qux',
            (object)[
                'foo' => $value,
            ],
        );

        $this->assertSame($innerResult, $result);
    }

    /**
     * Strict and context property missing.
     *
     * Test that a missing context property will give an invalid result.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyDependentProcessor::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyDependentProcessor::addProperty()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyDependentProcessor::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyDependentProcessor::findProperty()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyDependentProcessor::getTemplates()
     */
    public function testStrictAndContextPropertyMissing(): void
    {
        $processor1 = $this->createMock(ProcessorInterface::class);
        $processor1
            ->expects($this->never())
            ->method('process');

        $processor2 = $this->createMock(ProcessorInterface::class);
        $processor2
            ->expects($this->never())
            ->method('process');

        $processor = new PropertyDependentProcessor('qux', [
            ['bar', $processor1],
            ['baz', $processor2],
        ]);
        $result = $processor->process(
            'qux',
            (object)[
                'foo' => 'bar',
            ],
        );

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Strict and processor missing.
     *
     * Test that missing processor will give an invalid result.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyDependentProcessor::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyDependentProcessor::addProperty()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyDependentProcessor::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyDependentProcessor::findProperty()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyDependentProcessor::getTemplates()
     */
    public function testStrictAndProcessorMissing(): void
    {
        $innerProcessor1 = $this->createMock(ProcessorInterface::class);
        $innerProcessor1
            ->expects($this->never())
            ->method('process');

        $innerProcessor2 = $this->createMock(ProcessorInterface::class);
        $innerProcessor2
            ->expects($this->never())
            ->method('process');

        $processor = new PropertyDependentProcessor('foo', [
            ['qux', $innerProcessor1],
            ['baz', $innerProcessor2],
        ]);
        $result = $processor->process(
            'qux',
            (object)[
                'foo' => 'bar',
            ],
        );

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Non-strict and context property missing.
     *
     * Test that a missing context property will give a valid result.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyDependentProcessor::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyDependentProcessor::addProperty()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyDependentProcessor::process()
     */
    public function testNonStrictAndContextPropertyMissing(): void
    {
        $innerProcessor1 = $this->createMock(ProcessorInterface::class);
        $innerProcessor1
            ->expects($this->never())
            ->method('process');

        $innerProcessor2 = $this->createMock(ProcessorInterface::class);
        $innerProcessor2
            ->expects($this->never())
            ->method('process');

        $processor = new PropertyDependentProcessor('qux', [
            ['bar', $innerProcessor1],
            ['baz', $innerProcessor2],
        ], false);
        $result = $processor->process(
            'qux',
            (object)[
                'foo' => 'bar',
            ],
        );

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('qux', $result->getValue());
    }

    /**
     * Non-strict and processor missing.
     *
     * Test that missing processor will give an invalid result.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyDependentProcessor::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyDependentProcessor::addProperty()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyDependentProcessor::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyDependentProcessor::findProperty()
     */
    public function testNonStrictAndProcessorMissing(): void
    {
        $innerProcessor1 = $this->createMock(ProcessorInterface::class);
        $innerProcessor1
            ->expects($this->never())
            ->method('process');

        $innerProcessor2 = $this->createMock(ProcessorInterface::class);
        $innerProcessor2
            ->expects($this->never())
            ->method('process');

        $processor = new PropertyDependentProcessor('foo', [
            ['qux', $innerProcessor1],
            ['baz', $innerProcessor2],
        ], false);
        $result = $processor->process(
            'qux',
            (object)[
                'foo' => 'bar',
            ],
        );

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('qux', $result->getValue());
    }

    /**
     * Missing property and wildcard processor.
     *
     * Test that a missing property will be processed by a wildcard processor.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyDependentProcessor::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyDependentProcessor::addProperty()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyDependentProcessor::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyDependentProcessor::findProperty()
     */
    public function testMissingPropertyAndWildcardProcessor(): void
    {
        $innerResult = $this->createMock(ResultInterface::class);
        $innerResult
            ->method('isValid')
            ->willReturn(true);

        $innerProcessor = $this->createMock(ProcessorInterface::class);
        $innerProcessor
            ->expects($this->once())
            ->method('process')
            ->with('qux')
            ->willReturn($innerResult);

        $processor = new PropertyDependentProcessor('foo', [
            ['*', $innerProcessor],
        ], false);
        $result = $processor->process(
            'qux',
            (object)[
                'foo' => 'bar',
            ],
        );

        $this->assertSame($innerResult, $result);
    }

    /**
     * Not object.
     *
     * Test that non-object context cannot be processed.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyDependentProcessor::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyDependentProcessor::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyDependentProcessor::getTemplates()
     */
    public function testNotObject(): void
    {
        $processor = new PropertyDependentProcessor('foo');
        $result = $processor->process('test', []);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
