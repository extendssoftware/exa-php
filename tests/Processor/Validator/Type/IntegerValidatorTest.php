<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Type;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class IntegerValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that the integer value '-9' is a valid integer.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\IntegerValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\IntegerValidator::process()
     */
    public function testValid(): void
    {
        $validator = new IntegerValidator();
        $result = $validator->process(-9);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame(-9, $result->getValue());
    }

    /**
     * Unsigned.
     *
     * Test that integer value '-9' is not a valid unsigned integer.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\IntegerValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\IntegerValidator::process()
     */
    public function testUnsigned(): void
    {
        $validator = new IntegerValidator(true);
        $result = $validator->process(-9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Invalid.
     *
     * Test that string value 'foo' is an valid integer.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\IntegerValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\IntegerValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\IntegerValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new IntegerValidator();
        $result = $validator->process('foo');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * String.
     *
     * Test that a string representation of an integer is allowed.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\IntegerValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\IntegerValidator::process()
     */
    public function testString(): void
    {
        $validator = new IntegerValidator(allowString: true);
        $result = $validator->process('5');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('5', $result->getValue());
    }
}
