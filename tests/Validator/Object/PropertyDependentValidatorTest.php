<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Object;

use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class PropertyDependentValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that object is valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::addProperty()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::validate()
     */
    public function testIsValid(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->method('isValid')
            ->willReturn(true);

        $validator1 = $this->createMock(ValidatorInterface::class);
        $validator1
            ->expects($this->once())
            ->method('validate')
            ->with('qux')
            ->willReturn($result);

        $validator2 = $this->createMock(ValidatorInterface::class);
        $validator2
            ->expects($this->never())
            ->method('validate');

        $properties = new PropertyDependentValidator('foo', [
            'bar' => $validator1,
            'baz' => $validator2,
        ]);
        $result = $properties->validate('qux', (object)[
            'foo' => 'bar',
        ]);

        $this->assertTrue($result->isValid());
    }

    /**
     * Strict and context property missing.
     *
     * Test that missing context property will give invalid result.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::addProperty()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::getTemplates()
     */
    public function testStrictAndContextPropertyMissing(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->method('isValid')
            ->willReturn(true);

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
        $result = $validator->validate('qux', (object)[
            'foo' => 'bar',
        ]);

        $this->assertFalse($result->isValid());
    }

    /**
     * Strict and validator missing.
     *
     * Test that missing validator will give invalid result.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::addProperty()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::getTemplates()
     */
    public function testStrictAndValidatorMissing(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->method('isValid')
            ->willReturn(true);

        $validator1 = $this->createMock(ValidatorInterface::class);
        $validator1
            ->expects($this->never())
            ->method('validate');

        $validator2 = $this->createMock(ValidatorInterface::class);
        $validator2
            ->expects($this->never())
            ->method('validate');

        $validator = new PropertyDependentValidator('foo', [
            'qux' => $validator1,
            'baz' => $validator2,
        ]);
        $result = $validator->validate('qux', (object)[
            'foo' => 'bar',
        ]);

        $this->assertFalse($result->isValid());
    }

    /**
     * Non-strict and context property missing.
     *
     * Test that missing context property will give valid result.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::addProperty()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::validate()
     */
    public function testNonStrictAndContextPropertyMissing(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->method('isValid')
            ->willReturn(true);

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
        ], false);
        $result = $validator->validate('qux', (object)[
            'foo' => 'bar',
        ]);

        $this->assertTrue($result->isValid());
    }

    /**
     * Non-strict and validator missing.
     *
     * Test that missing validator will give invalid result.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::addProperty()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::validate()
     */
    public function testNonStrictAndValidatorMissing(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->method('isValid')
            ->willReturn(true);

        $validator1 = $this->createMock(ValidatorInterface::class);
        $validator1
            ->expects($this->never())
            ->method('validate');

        $validator2 = $this->createMock(ValidatorInterface::class);
        $validator2
            ->expects($this->never())
            ->method('validate');

        $validator = new PropertyDependentValidator('foo', [
            'qux' => $validator1,
            'baz' => $validator2,
        ], false);
        $result = $validator->validate('qux', (object)[
            'foo' => 'bar',
        ]);

        $this->assertTrue($result->isValid());
    }

    /**
     * Missing property and wildcard validator.
     *
     * Test that missing property will be validated by wildcard validator.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::addProperty()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::validate()
     */
    public function testMissingPropertyAndWildcardValidator(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->method('isValid')
            ->willReturn(true);

        $validator = $this->createMock(ValidatorInterface::class);
        $validator
            ->expects($this->once())
            ->method('validate')
            ->with('qux')
            ->willReturn($result);

        $validator = new PropertyDependentValidator('foo', [
            '*' => $validator,
        ], false);
        $result = $validator->validate('qux', (object)[
            'foo' => 'bar',
        ]);

        $this->assertTrue($result->isValid());
    }

    /**
     * Not object.
     *
     * Test that non object context can not be validated.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyDependentValidator::getTemplates()
     */
    public function testNotObject(): void
    {
        $validator = new PropertyDependentValidator('foo');
        $result = $validator->validate('test', []);

        $this->assertFalse($result->isValid());
    }
}
