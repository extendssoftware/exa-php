<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Type;

use PHPUnit\Framework\TestCase;

class IntegerValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that integer value '-9' is a valid integer.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\IntegerValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\IntegerValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new IntegerValidator();
        $result = $validator->validate(-9);

        $this->assertTrue($result->isValid());
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

        $this->assertFalse($result->isValid());
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

        $this->assertFalse($result->isValid());
    }
}
