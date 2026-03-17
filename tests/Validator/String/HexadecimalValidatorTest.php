<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class HexadecimalValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that a string consists of hexadecimal digits.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\HexadecimalValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new HexadecimalValidator();
        $result1 = $validator->validate('AB10BC99');
        $result2 = $validator->validate('ab12bc99');

        $this->assertInstanceOf(ValidResult::class, $result1);
        $this->assertSame('AB10BC99', $result1->getValue());

        $this->assertInstanceOf(ValidResult::class, $result2);
        $this->assertSame('ab12bc99', $result2->getValue());
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
        $result = $validator->validate('AR1012');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Invalid.
     *
     * Test that a none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\HexadecimalValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new HexadecimalValidator();
        $result = $validator->validate(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
