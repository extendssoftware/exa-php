<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Other\Object;

use ExtendsSoftware\ExaPHP\Processor\Other\ProxyProcessor;
use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\Container\Object\ObjectContainerResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use PHPUnit\Framework\TestCase;

class PropertiesProcessorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that an object is valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertiesProcessor::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertiesProcessor::addProperty()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertiesProcessor::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertiesProcessor::checkStrictness()
     */
    public function testIsValid(): void
    {
        $object = (object)[
            'foo' => 'bar',
            'bar' => 'baz',
            'baz' => 'qux',
        ];

        $innerResult = $this->createMock(ResultInterface::class);
        $innerResult
            ->method('isValid')
            ->willReturn(true);

        $innerProcessor = $this->createMock(ProcessorInterface::class);
        $innerProcessor
            ->expects($this->exactly(3))
            ->method('process')
            ->willReturn($innerResult);

        $processor = new PropertiesProcessor([
            'foo' => $innerProcessor,
            'bar' => $innerProcessor,
            'baz' => $innerProcessor,
            'qux' => new ProxyProcessor($innerProcessor),
        ]);
        $result = $processor->process($object, 'context');

        $this->assertInstanceOf(ObjectContainerResult::class, $result);
        $this->assertTrue($result->isValid());
    }

    /**
     * Strict.
     *
     * Test that undefined object property is not allowed.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertiesProcessor::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertiesProcessor::addProperty()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertiesProcessor::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertiesProcessor::checkStrictness()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertiesProcessor::getTemplates()
     */
    public function testStrict(): void
    {
        $object = (object)[
            'foo' => 'bar',
            'bar' => 'baz',
        ];

        $innerResult = $this->createMock(ResultInterface::class);
        $innerResult
            ->method('isValid')
            ->willReturn(true);

        $innerProcessor = $this->createMock(ProcessorInterface::class);
        $innerProcessor
            ->expects($this->once())
            ->method('process')
            ->with('bar', $object)
            ->willReturn($innerResult);

        $processor = new PropertiesProcessor([
            'foo' => $innerProcessor,
        ]);
        $result = $processor->process($object, 'context');

        $this->assertInstanceOf(ObjectContainerResult::class, $result);
        $this->assertFalse($result->isValid());
    }

    /**
     * Not strict.
     *
     * Test that undefined object properties are allowed.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertiesProcessor::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertiesProcessor::process()
     */
    public function testNotStrict(): void
    {
        $object = (object)[
            'foo' => 'bar',
            'bar' => 'baz',
        ];

        $processor = new PropertiesProcessor(null, false);
        $result = $processor->process($object, 'context');

        $this->assertInstanceOf(ObjectContainerResult::class, $result);
        $this->assertTrue($result->isValid());
    }

    /**
     * Property missing.
     *
     * Test that a missing property will give an invalid result.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertiesProcessor::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertiesProcessor::addProperty()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertiesProcessor::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertiesProcessor::getTemplates()
     */
    public function testPropertyMissing(): void
    {
        $innerProcessor = $this->createMock(ProcessorInterface::class);
        $innerProcessor
            ->expects($this->never())
            ->method('process');

        $processor = new PropertiesProcessor([
            'foo' => $innerProcessor,
        ]);
        $result = $processor->process((object)[], 'context');

        $this->assertInstanceOf(ObjectContainerResult::class, $result);
        $this->assertFalse($result->isValid());
    }

    /**
     * Not object.
     *
     * Test that a non-object cannot be processed.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertiesProcessor::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertiesProcessor::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertiesProcessor::getTemplates()
     */
    public function testNotObject(): void
    {
        $processor = new PropertiesProcessor();
        $result = $processor->process([]);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Value as context.
     *
     * Test that value will be passed as context.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertiesProcessor::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertiesProcessor::process()
     */
    public function testValueAsContext(): void
    {
        $object = (object)[
            'foo' => 'bar',
        ];

        $innerResult = $this->createMock(ResultInterface::class);
        $innerResult
            ->method('isValid')
            ->willReturn(true);

        $innerProcessor = $this->createMock(ProcessorInterface::class);
        $innerProcessor
            ->expects($this->exactly(2))
            ->method('process')
            ->willReturn($innerResult);

        $processor = new PropertiesProcessor([
            'foo' => $innerProcessor,
        ]);

        $result1 = $processor->process($object);
        $result2 = $processor->process($object, 'foo');

        $this->assertInstanceOf(ObjectContainerResult::class, $result1);
        $this->assertTrue($result1->isValid());

        $this->assertInstanceOf(ObjectContainerResult::class, $result2);
        $this->assertTrue($result2->isValid());
    }
}
