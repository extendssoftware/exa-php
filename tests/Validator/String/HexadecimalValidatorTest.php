<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use PHPUnit\Framework\TestCase;

class HexadecimalValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that string consist of hexadecimal digits.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\HexadecimalValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new HexadecimalValidator();

        $this->assertTrue($validator->validate('AB10BC99')->isValid());
        $this->assertTrue($validator->validate('ab12bc99')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that string does not consist of hexadecimal digits.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\HexadecimalValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\HexadecimalValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new HexadecimalValidator();

        $this->assertFalse($validator->validate('AR1012')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\HexadecimalValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new HexadecimalValidator();
        $result = $validator->validate(9);

        $this->assertFalse($result->isValid());
    }
}
