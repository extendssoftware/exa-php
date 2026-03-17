<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Object;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class PropertyValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that an object property exists.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyValidator::validate()
     */
    public function testValid(): void
    {
        $object = (object)[
            'foo' => 'bar',
        ];

        $validator = new PropertyValidator('foo');
        $result = $validator->validate($object);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame($object, $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that object property does not exist.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new PropertyValidator('foo');
        $result = $validator->validate((object)[
            'bar' => 'foo',
        ]);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Not object.
     *
     * Test that a value is not an object.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyValidator::getTemplates()
     */
    public function testNotObject(): void
    {
        $validator = new PropertyValidator('foo');
        $result = $validator->validate([
            'bar' => 'foo',
        ]);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
