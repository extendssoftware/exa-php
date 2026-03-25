<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Object;

use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\Container\Object\ObjectContainerResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use PHPUnit\Framework\TestCase;

class SchemaValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that object schema is valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Object\SchemaValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Object\SchemaValidator::process()
     */
    public function testIsValid(): void
    {
        $object = (object)[
            'foo' => 'bar',
            'bar' => 'baz',
            'baz' => 'qux',
        ];

        $result = $this->createMock(ResultInterface::class);
        $result
            ->expects($this->exactly(6))
            ->method('isValid')
            ->willReturn(true);

        $propertyValidator = $this->createMock(ProcessorInterface::class);
        $propertyValidator
            ->expects($this->exactly(3))
            ->method('process')
            ->willReturn($result);

        $valueValidator = $this->createMock(ProcessorInterface::class);
        $valueValidator
            ->expects($this->exactly(3))
            ->method('process')
            ->willReturn($result);

        $schema = new SchemaValidator($propertyValidator, $valueValidator);
        $result = $schema->process($object, 'context');

        $this->assertInstanceOf(ObjectContainerResult::class, $result);
        $this->assertTrue($result->isValid());
    }

    /**
     * Not object.
     *
     * Test that a non-object cannot be validated.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Object\SchemaValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Object\SchemaValidator::process()
     */
    public function testNotObject(): void
    {
        $properties = new SchemaValidator();
        $result = $properties->process([]);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Invalid object property.
     *
     * Test that an object property is invalid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Object\SchemaValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Object\SchemaValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Object\SchemaValidator::getTemplates()
     */
    public function testInvalidObjectProperty(): void
    {
        $object = (object)[
            'foo' => 'bar',
            'bar' => 'baz',
            'propertyNameTooLong' => 'qux',
        ];

        $validResult = $this->createMock(ResultInterface::class);
        $validResult
            ->expects($this->exactly(5))
            ->method('isValid')
            ->willReturn(true);

        $invalidResult = $this->createMock(ResultInterface::class);
        $invalidResult
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(false);

        $propertyValidator = $this->createMock(ProcessorInterface::class);
        $propertyValidator
            ->expects($this->exactly(3))
            ->method('process')
            ->willReturnCallback(fn($value, $context) => match ([$value, $context]) {
                ['propertyNameTooLong', $context] => $invalidResult,
                default => $validResult,
            });

        $valueValidator = $this->createMock(ProcessorInterface::class);
        $valueValidator
            ->expects($this->exactly(3))
            ->method('process')
            ->willReturn($validResult);

        $schema = new SchemaValidator($propertyValidator, $valueValidator);
        $result = $schema->process($object, 'context');

        $this->assertInstanceOf(ObjectContainerResult::class, $result);
        $this->assertFalse($result->isValid());
    }

    /**
     * Invalid property value.
     *
     * Test that a property value is invalid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Object\SchemaValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Object\SchemaValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Object\SchemaValidator::getTemplates()
     */
    public function testInvalidPropertyValue(): void
    {
        $object = (object)[
            'foo' => 'bar',
            'bar' => 'baz',
            'baz' => 'propertyValueTooLong',
        ];

        $validResult = $this->createMock(ResultInterface::class);
        $validResult
            ->expects($this->exactly(5))
            ->method('isValid')
            ->willReturn(true);

        $invalidResult = $this->createMock(ResultInterface::class);
        $invalidResult
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(false);

        $propertyValidator = $this->createMock(ProcessorInterface::class);
        $propertyValidator
            ->expects($this->exactly(3))
            ->method('process')
            ->willReturn($validResult);

        $valueValidator = $this->createMock(ProcessorInterface::class);
        $valueValidator
            ->expects($this->exactly(3))
            ->method('process')
            ->willReturnCallback(fn($value, $context) => match ([$value, $context]) {
                ['propertyValueTooLong', $context] => $invalidResult,
                default => $validResult,
            });

        $schema = new SchemaValidator($propertyValidator, $valueValidator);
        $result = $schema->process($object, 'context');

        $this->assertInstanceOf(ObjectContainerResult::class, $result);
        $this->assertFalse($result->isValid());
    }

    /**
     * Too many properties.
     *
     * Test that there are more properties than allowed.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Object\SchemaValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Object\SchemaValidator::process()
     */
    public function testTooManyProperties(): void
    {
        $object = (object)[
            'foo' => 'bar',
            'bar' => 'baz',
            'baz' => 'qux',
        ];

        $result = $this->createMock(ResultInterface::class);
        $result
            ->expects($this->exactly(4))
            ->method('isValid')
            ->willReturn(true);

        $propertyValidator = $this->createMock(ProcessorInterface::class);
        $propertyValidator
            ->expects($this->exactly(2))
            ->method('process')
            ->willReturn($result);

        $valueValidator = $this->createMock(ProcessorInterface::class);
        $valueValidator
            ->expects($this->exactly(2))
            ->method('process')
            ->willReturn($result);

        $schema = new SchemaValidator($propertyValidator, $valueValidator, 2);
        $result = $schema->process($object, 'context');

        $this->assertInstanceOf(ObjectContainerResult::class, $result);
        $this->assertFalse($result->isValid());
    }
}
