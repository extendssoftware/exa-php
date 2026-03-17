<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Type;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class IntegerValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that the integer value '-9' is a valid integer.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\IntegerValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\IntegerValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new IntegerValidator();
        $result = $validator->validate(-9);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame(-9, $result->getValue());
    }

    /**
     * Unsigned.
     *
     * Test that integer value '-9' is not a valid unsigned integer.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\IntegerValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\IntegerValidator::validate()
     */
    public function testUnsigned(): void
    {
        $validator = new IntegerValidator(true);
        $result = $validator->validate(-9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Invalid.
     *
     * Test that string value 'foo' is an valid integer.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\IntegerValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\IntegerValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\IntegerValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new IntegerValidator();
        $result = $validator->validate('foo');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * String.
     *
     * Test that a string representation of an integer is allowed.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\IntegerValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\IntegerValidator::validate()
     */
    public function testString(): void
    {
        $validator = new IntegerValidator(allowString: true);
        $result = $validator->validate('5');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('5', $result->getValue());
    }
}
