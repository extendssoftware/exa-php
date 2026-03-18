<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Collection;

use ExtendsSoftware\ExaPHP\Validator\Result\Container\Array\ArrayContainerResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class ContainsValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that a collection will be valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\ContainsValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\ContainsValidator::validate()
     */
    public function testValid(): void
    {
        $innerResult = $this->createMock(ResultInterface::class);
        $innerResult
            ->method('isValid')
            ->willReturn(true);

        $innerValidator = $this->createMock(ValidatorInterface::class);
        $innerValidator
            ->expects($this->exactly(3))
            ->method('validate')
            ->with('foo', 'bar')
            ->willReturn($innerResult);

        $collection = new ContainsValidator($innerValidator);
        $result = $collection->validate([
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
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\ContainsValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\ContainsValidator::validate()
     */
    public function testInvalid(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->method('isValid')
            ->willReturn(false);

        $validator = $this->createMock(ValidatorInterface::class);
        $validator
            ->expects($this->exactly(3))
            ->method('validate')
            ->with('foo', 'bar')
            ->willReturn($result);

        $collection = new ContainsValidator($validator);
        $result = $collection->validate([
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
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\ContainsValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\ContainsValidator::validate()
     */
    public function testNotIterable(): void
    {
        $validator = $this->createMock(ValidatorInterface::class);

        $collection = new ContainsValidator($validator);
        $result = $collection->validate(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
