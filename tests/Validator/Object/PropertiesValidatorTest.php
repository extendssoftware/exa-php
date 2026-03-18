<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Object;

use ExtendsSoftware\ExaPHP\Validator\Other\ProxyValidator;
use ExtendsSoftware\ExaPHP\Validator\Result\Container\Object\ObjectContainerResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class PropertiesValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that an object is valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::addProperty()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::checkStrictness()
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

        $innerValidator = $this->createMock(ValidatorInterface::class);
        $innerValidator
            ->expects($this->exactly(3))
            ->method('validate')
            ->willReturn($innerResult);

        $validator = new PropertiesValidator([
            'foo' => $innerValidator,
            'bar' => $innerValidator,
            'baz' => $innerValidator,
            'qux' => new ProxyValidator($innerValidator),
        ]);
        $result = $validator->validate($object, 'context');

        $this->assertInstanceOf(ObjectContainerResult::class, $result);
        $this->assertTrue($result->isValid());
    }

    /**
     * Strict.
     *
     * Test that undefined object property is not allowed.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::addProperty()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::checkStrictness()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::getTemplates()
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

        $innerValidator = $this->createMock(ValidatorInterface::class);
        $innerValidator
            ->expects($this->once())
            ->method('validate')
            ->with('bar', $object)
            ->willReturn($innerResult);

        $validator = new PropertiesValidator([
            'foo' => $innerValidator,
        ]);
        $result = $validator->validate($object, 'context');

        $this->assertInstanceOf(ObjectContainerResult::class, $result);
        $this->assertFalse($result->isValid());
    }

    /**
     * Not strict.
     *
     * Test that undefined object properties are allowed.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::validate()
     */
    public function testNotStrict(): void
    {
        $object = (object)[
            'foo' => 'bar',
            'bar' => 'baz',
        ];

        $validator = new PropertiesValidator(null, false);
        $result = $validator->validate($object, 'context');

        $this->assertInstanceOf(ObjectContainerResult::class, $result);
        $this->assertTrue($result->isValid());
    }

    /**
     * Property missing.
     *
     * Test that a missing property will give an invalid result.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::addProperty()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::getTemplates()
     */
    public function testPropertyMissing(): void
    {
        $innerValidator = $this->createMock(ValidatorInterface::class);
        $innerValidator
            ->expects($this->never())
            ->method('validate');

        $validator = new PropertiesValidator([
            'foo' => $innerValidator,
        ]);
        $result = $validator->validate((object)[], 'context');

        $this->assertInstanceOf(ObjectContainerResult::class, $result);
        $this->assertFalse($result->isValid());
    }

    /**
     * Not object.
     *
     * Test that a non-object cannot be validated.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::getTemplates()
     */
    public function testNotObject(): void
    {
        $validator = new PropertiesValidator();
        $result = $validator->validate([]);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Value as context.
     *
     * Test that value will be passed as context.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::validate()
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

        $innerValidator = $this->createMock(ValidatorInterface::class);
        $innerValidator
            ->expects($this->exactly(2))
            ->method('validate')
            ->willReturn($innerResult);

        $validator = new PropertiesValidator([
            'foo' => $innerValidator,
        ]);

        $result1 = $validator->validate($object);
        $result2 = $validator->validate($object, 'foo');

        $this->assertInstanceOf(ObjectContainerResult::class, $result1);
        $this->assertTrue($result1->isValid());

        $this->assertInstanceOf(ObjectContainerResult::class, $result2);
        $this->assertTrue($result2->isValid());
    }
}
