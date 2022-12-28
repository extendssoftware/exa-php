<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Object;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class PropertiesValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that object is valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::addProperty()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::checkStrictness()
     */
    public function testIsValid(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->method('isValid')
            ->willReturn(true);

        $validator = $this->createMock(ValidatorInterface::class);
        $validator
            ->expects($this->exactly(3))
            ->method('validate')
            ->withConsecutive(
                ['bar', 'context'],
                ['baz', 'context'],
                ['qux', 'context']
            )
            ->willReturn($result);

        $properties = new PropertiesValidator([
            'foo' => $validator,
            'bar' => $validator,
            'baz' => $validator,
            'qux' => [$validator, true],
        ]);
        $result = $properties->validate((object)[
            'foo' => 'bar',
            'bar' => 'baz',
            'baz' => 'qux',
        ], 'context');

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
        $result = $this->createMock(ResultInterface::class);
        $result
            ->method('isValid')
            ->willReturn(true);

        $validator = $this->createMock(ValidatorInterface::class);
        $validator
            ->expects($this->once())
            ->method('validate')
            ->with('bar', 'context')
            ->willReturn($result);

        $properties = new PropertiesValidator([
            'foo' => $validator,
        ]);
        $result = $properties->validate((object)[
            'foo' => 'bar',
            'bar' => 'baz',
        ], 'context');

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
        $properties = new PropertiesValidator(null, false);
        $result = $properties->validate((object)[
            'foo' => 'bar',
            'bar' => 'baz',
        ], 'context');

        $this->assertTrue($result->isValid());
    }

    /**
     * Property missing.
     *
     * Test that missing property will give invalid result.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::addProperty()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::getTemplates()
     */
    public function testPropertyMissing(): void
    {
        $validator = $this->createMock(ValidatorInterface::class);
        $validator
            ->expects($this->never())
            ->method('validate');

        $properties = new PropertiesValidator([
            'foo' => $validator,
        ]);
        $result = $properties->validate((object)[], 'context');

        $this->assertFalse($result->isValid());
    }

    /**
     * Not object.
     *
     * Test that a non object can not be validated.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::getTemplates()
     */
    public function testNotObject(): void
    {
        $properties = new PropertiesValidator();
        $result = $properties->validate([]);

        $this->assertFalse($result->isValid());
    }

    /**
     * Value as context.
     *
     * Test that value will be passed as context when context is null.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::validate()
     */
    public function testValueAsContext(): void
    {
        $object = (object)[
            'foo' => 'bar',
        ];

        $result = $this->createMock(ResultInterface::class);
        $result
            ->method('isValid')
            ->willReturn(true);

        $validator = $this->createMock(ValidatorInterface::class);
        $validator
            ->expects($this->exactly(2))
            ->method('validate')
            ->withConsecutive(
                ['bar', $object],
                ['bar', 'foo']
            )
            ->willReturn($result);

        $properties = new PropertiesValidator([
            'foo' => $validator,
        ]);

        $this->assertTrue($properties->validate($object)->isValid());
        $this->assertTrue($properties->validate($object, 'foo')->isValid());
    }

    /**
     * Factory.
     *
     * Test that factory will return correct instance.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertiesValidator::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('getService')
            ->with(ValidatorInterface::class, ['foo' => 'bar'])
            ->willReturn($this->createMock(ValidatorInterface::class));

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $properties = PropertiesValidator::factory(PropertiesValidator::class, $serviceLocator, [
            'properties' => [
                [
                    'property' => 'foo',
                    'validator' => [
                        'name' => ValidatorInterface::class,
                        'options' => [
                            'foo' => 'bar',
                        ],
                    ],
                    'optional' => false,
                ],
            ],
            'strict' => false,
        ]);

        $this->assertInstanceOf(PropertiesValidator::class, $properties);
    }
}
