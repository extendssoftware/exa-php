<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Object;

use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class SchemaValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that object schema is valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\SchemaValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\SchemaValidator::validate()
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

        $propertyValidator = $this->createMock(ValidatorInterface::class);
        $propertyValidator
            ->expects($this->exactly(3))
            ->method('validate')
            ->willReturnCallback(fn($value, $context) => match ([$value, $context]) {
                ['foo', $context],
                ['bar', $context],
                ['baz', $context] => $result
            });

        $valueValidator = $this->createMock(ValidatorInterface::class);
        $valueValidator
            ->expects($this->exactly(3))
            ->method('validate')
            ->willReturnCallback(fn($value, $context) => match ([$value, $context]) {
                ['bar', $context],
                ['baz', $context],
                ['qux', $context] => $result
            });

        /**
         * @param ValidatorInterface $propertyValidator
         * @param ValidatorInterface $valueValidator
         */
        $schema = new SchemaValidator($propertyValidator, $valueValidator);
        $result = $schema->validate($object, 'context');

        $this->assertTrue($result->isValid());
    }

    /**
     * Not object.
     *
     * Test that a non object can not be validated.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\SchemaValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\SchemaValidator::validate()
     */
    public function testNotObject(): void
    {
        $properties = new SchemaValidator();
        $result = $properties->validate([]);

        $this->assertFalse($result->isValid());
    }

    /**
     * Invalid object property.
     *
     * Test that object property is invalid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\SchemaValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\SchemaValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\SchemaValidator::getTemplates()
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

        $propertyValidator = $this->createMock(ValidatorInterface::class);
        $propertyValidator
            ->expects($this->exactly(3))
            ->method('validate')
            ->willReturnCallback(fn($value, $context) => match ([$value, $context]) {
                ['foo', $context],
                ['bar', $context] => $validResult,
                ['propertyNameTooLong', $context] => $invalidResult
            });

        $valueValidator = $this->createMock(ValidatorInterface::class);
        $valueValidator
            ->expects($this->exactly(3))
            ->method('validate')
            ->willReturnCallback(fn($value, $context) => match ([$value, $context]) {
                ['bar', $context],
                ['baz', $context],
                ['qux', $context] => $validResult
            });

        /**
         * @param ValidatorInterface $propertyValidator
         * @param ValidatorInterface $valueValidator
         */
        $schema = new SchemaValidator($propertyValidator, $valueValidator);
        $result = $schema->validate($object, 'context');

        $this->assertFalse($result->isValid());
    }

    /**
     * Invalid property value.
     *
     * Test that property value is invalid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\SchemaValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\SchemaValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\SchemaValidator::getTemplates()
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

        $propertyValidator = $this->createMock(ValidatorInterface::class);
        $propertyValidator
            ->expects($this->exactly(3))
            ->method('validate')
            ->willReturnCallback(fn($value, $context) => match ([$value, $context]) {
                ['foo', $context],
                ['bar', $context],
                ['baz', $context] => $validResult,
            });

        $valueValidator = $this->createMock(ValidatorInterface::class);
        $valueValidator
            ->expects($this->exactly(3))
            ->method('validate')
            ->willReturnCallback(fn($value, $context) => match ([$value, $context]) {
                ['bar', $context],
                ['baz', $context] => $validResult,
                ['propertyValueTooLong', $context] => $invalidResult,
            });

        /**
         * @param ValidatorInterface $propertyValidator
         * @param ValidatorInterface $valueValidator
         */
        $schema = new SchemaValidator($propertyValidator, $valueValidator);
        $result = $schema->validate($object, 'context');

        $this->assertFalse($result->isValid());
    }

    /**
     * Too many properties.
     *
     * Test that there are more properties than allowed.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\SchemaValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\SchemaValidator::validate()
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

        $propertyValidator = $this->createMock(ValidatorInterface::class);
        $propertyValidator
            ->expects($this->exactly(2))
            ->method('validate')
            ->willReturnCallback(fn($value, $context) => match ([$value, $context]) {
                ['foo', $context],
                ['bar', $context] => $result
            });

        $valueValidator = $this->createMock(ValidatorInterface::class);
        $valueValidator
            ->expects($this->exactly(2))
            ->method('validate')
            ->willReturnCallback(fn($value, $context) => match ([$value, $context]) {
                ['bar', $context],
                ['baz', $context] => $result
            });

        /**
         * @param ValidatorInterface $propertyValidator
         * @param ValidatorInterface $valueValidator
         */
        $schema = new SchemaValidator($propertyValidator, $valueValidator, 2);
        $result = $schema->validate($object, 'context');

        $this->assertFalse($result->isValid());
    }

}
