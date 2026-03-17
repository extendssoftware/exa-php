<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class PunctuationValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that a string consists of punctuation characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\PunctuationValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new PunctuationValidator();
        $result = $validator->validate('*&$()');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('*&$()', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that string does not consist of punctuation characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\PunctuationValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\PunctuationValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new PunctuationValidator();
        $result1 = $validator->validate('Xyz!@!$#');
        $result2 = $validator->validate('!@ # $');

        $this->assertInstanceOf(InvalidResult::class, $result1);
        $this->assertInstanceOf(InvalidResult::class, $result2);
    }

    /**
     * Invalid.
     *
     * Test that a none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\PunctuationValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new PunctuationValidator();
        $result = $validator->validate(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
