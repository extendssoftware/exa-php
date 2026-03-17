<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Object;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class PropertyDependentValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that an object is valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::addProperty()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::validate()
     */
    public function testIsValid(): void
    {
        $innerResult = $this->createMock(ResultInterface::class);
        $innerResult
            ->method('isValid')
            ->willReturn(true);

        $innerValidator1 = $this->createMock(ValidatorInterface::class);
        $innerValidator1
            ->expects($this->once())
            ->method('validate')
            ->with('qux')
            ->willReturn($innerResult);

        $innerValidator2 = $this->createMock(ValidatorInterface::class);
        $innerValidator2
            ->expects($this->never())
            ->method('validate');

        $validator = new PropertyDependentValidator('foo', [
            'bar' => $innerValidator1,
            'baz' => $innerValidator2,
        ]);
        $result = $validator->validate(
            'qux',
            (object)[
                'foo' => 'bar',
            ],
        );

        $this->assertSame($innerResult, $result);
    }

    /**
     * Strict and context property missing.
     *
     * Test that a missing context property will give an invalid result.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::addProperty()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::getTemplates()
     */
    public function testStrictAndContextPropertyMissing(): void
    {
        $validator1 = $this->createMock(ValidatorInterface::class);
        $validator1
            ->expects($this->never())
            ->method('validate');

        $validator2 = $this->createMock(ValidatorInterface::class);
        $validator2
            ->expects($this->never())
            ->method('validate');

        $validator = new PropertyDependentValidator('qux', [
            'bar' => $validator1,
            'baz' => $validator2,
        ]);
        $result = $validator->validate(
            'qux',
            (object)[
                'foo' => 'bar',
            ],
        );

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Strict and validator missing.
     *
     * Test that missing validator will give an invalid result.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::addProperty()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::getTemplates()
     */
    public function testStrictAndValidatorMissing(): void
    {
        $innerValidator1 = $this->createMock(ValidatorInterface::class);
        $innerValidator1
            ->expects($this->never())
            ->method('validate');

        $innerValidator2 = $this->createMock(ValidatorInterface::class);
        $innerValidator2
            ->expects($this->never())
            ->method('validate');

        $validator = new PropertyDependentValidator('foo', [
            'qux' => $innerValidator1,
            'baz' => $innerValidator2,
        ]);
        $result = $validator->validate(
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
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::addProperty()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::validate()
     */
    public function testNonStrictAndContextPropertyMissing(): void
    {
        $innerValidator1 = $this->createMock(ValidatorInterface::class);
        $innerValidator1
            ->expects($this->never())
            ->method('validate');

        $innerValidator2 = $this->createMock(ValidatorInterface::class);
        $innerValidator2
            ->expects($this->never())
            ->method('validate');

        $validator = new PropertyDependentValidator('qux', [
            'bar' => $innerValidator1,
            'baz' => $innerValidator2,
        ], false);
        $result = $validator->validate(
            'qux',
            (object)[
                'foo' => 'bar',
            ],
        );

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('qux', $result->getValue());
    }

    /**
     * Non-strict and validator missing.
     *
     * Test that missing validator will give an invalid result.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::addProperty()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::validate()
     */
    public function testNonStrictAndValidatorMissing(): void
    {
        $innerValidator1 = $this->createMock(ValidatorInterface::class);
        $innerValidator1
            ->expects($this->never())
            ->method('validate');

        $innerValidator2 = $this->createMock(ValidatorInterface::class);
        $innerValidator2
            ->expects($this->never())
            ->method('validate');

        $validator = new PropertyDependentValidator('foo', [
            'qux' => $innerValidator1,
            'baz' => $innerValidator2,
        ], false);
        $result = $validator->validate(
            'qux',
            (object)[
                'foo' => 'bar',
            ],
        );

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('qux', $result->getValue());
    }

    /**
     * Missing property and wildcard validator.
     *
     * Test that a missing property will be validated by a wildcard validator.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::addProperty()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::validate()
     */
    public function testMissingPropertyAndWildcardValidator(): void
    {
        $innerResult = $this->createMock(ResultInterface::class);
        $innerResult
            ->method('isValid')
            ->willReturn(true);

        $innerValidator = $this->createMock(ValidatorInterface::class);
        $innerValidator
            ->expects($this->once())
            ->method('validate')
            ->with('qux')
            ->willReturn($innerResult);

        $validator = new PropertyDependentValidator('foo', [
            '*' => $innerValidator,
        ], false);
        $result = $validator->validate(
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
     * Test that non-object context cannot be validated.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::getTemplates()
     */
    public function testNotObject(): void
    {
        $validator = new PropertyDependentValidator('foo');
        $result = $validator->validate('test', []);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
