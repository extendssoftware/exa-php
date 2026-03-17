<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class AlphabeticValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that a string consists of alphabetic characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\AlphabeticValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new AlphabeticValidator();
        $result = $validator->validate('KjgWZC');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('KjgWZC', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that string does not consist of alphabetic characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\AlphabeticValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\AlphabeticValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new AlphabeticValidator();
        $result = $validator->validate('arf12');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Invalid.
     *
     * Test that a none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\AlphabeticValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new AlphabeticValidator();
        $result = $validator->validate(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
