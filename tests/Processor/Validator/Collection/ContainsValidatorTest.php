<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Collection;

use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\Container\Array\ArrayContainerResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use PHPUnit\Framework\TestCase;

class ContainsValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that a collection will be valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Collection\ContainsValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Collection\ContainsValidator::process()
     */
    public function testValid(): void
    {
        $innerResult = $this->createMock(ResultInterface::class);
        $innerResult
            ->method('isValid')
            ->willReturn(true);

        $innerValidator = $this->createMock(ProcessorInterface::class);
        $innerValidator
            ->expects($this->exactly(3))
            ->method('process')
            ->with('foo', 'bar')
            ->willReturn($innerResult);

        $collection = new ContainsValidator($innerValidator);
        $result = $collection->process([
            'foo',
            'foo',
            'foo',
        ], 'bar');

        $this->assertInstanceOf(ArrayContainerResult::class, $result);
        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that a collection will be invalid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Collection\ContainsValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Collection\ContainsValidator::process()
     */
    public function testInvalid(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->method('isValid')
            ->willReturn(false);

        $validator = $this->createMock(ProcessorInterface::class);
        $validator
            ->expects($this->exactly(3))
            ->method('process')
            ->with('foo', 'bar')
            ->willReturn($result);

        $collection = new ContainsValidator($validator);
        $result = $collection->process([
            'foo',
            'foo',
            'foo',
        ], 'bar');

        $this->assertInstanceOf(ArrayContainerResult::class, $result);
        $this->assertFalse($result->isValid());
    }

    /**
     * Not iterable.
     *
     * Test that a collection will be invalid when the value is not iterable.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Collection\ContainsValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Collection\ContainsValidator::process()
     */
    public function testNotIterable(): void
    {
        $validator = $this->createMock(ProcessorInterface::class);

        $collection = new ContainsValidator($validator);
        $result = $collection->process(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
