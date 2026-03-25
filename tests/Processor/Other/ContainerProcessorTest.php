<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Other;

use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\Container\Array\ArrayContainerResult;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use PHPUnit\Framework\TestCase;

class ContainerProcessorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that sub processors will be processed and a valid result container will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\ContainerProcessor::addProcessor()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\ContainerProcessor::process()
     */
    public function testValid(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->expects($this->exactly(8))
            ->method('isValid')
            ->willReturn(true);

        $processor = $this->createMock(ProcessorInterface::class);
        $processor
            ->expects($this->exactly(4))
            ->method('process')
            ->with('foo', 'bar')
            ->willReturn($result);

        $container = new ContainerProcessor();
        $result = $container
            ->addProcessor($processor)
            ->addProcessor($processor)
            ->addProcessor($processor)
            ->addProcessor($processor)
            ->process('foo', 'bar');

        $this->assertInstanceOf(ArrayContainerResult::class, $result);
        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that sub processors will be processed and an invalid result container will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\ContainerProcessor::addProcessor()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\ContainerProcessor::process()
     */
    public function testInvalid(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->expects($this->exactly(5))
            ->method('isValid')
            ->willReturnOnConsecutiveCalls(
                true,
                true,
                false,
                false,
                false,
            );

        $processor = $this->createMock(ProcessorInterface::class);
        $processor
            ->expects($this->exactly(3))
            ->method('process')
            ->with('foo', 'bar')
            ->willReturn($result);

        $container = new ContainerProcessor();
        $result = $container
            ->addProcessor($processor)
            ->addProcessor($processor)
            ->addProcessor($processor, true)
            ->addProcessor($processor)
            ->process('foo', 'bar');

        $this->assertInstanceOf(ArrayContainerResult::class, $result);
        $this->assertFalse($result->isValid());
    }
}
