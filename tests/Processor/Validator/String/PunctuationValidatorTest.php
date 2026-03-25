<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\String;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class PunctuationValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that a string consists of punctuation characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\String\PunctuationValidator::process()
     */
    public function testValid(): void
    {
        $validator = new PunctuationValidator();
        $result = $validator->process('*&$()');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('*&$()', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that string does not consist of punctuation characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\String\PunctuationValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\String\PunctuationValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new PunctuationValidator();
        $result1 = $validator->process('Xyz!@!$#');
        $result2 = $validator->process('!@ # $');

        $this->assertInstanceOf(InvalidResult::class, $result1);
        $this->assertInstanceOf(InvalidResult::class, $result2);
    }

    /**
     * Invalid.
     *
     * Test that a none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\String\PunctuationValidator::process()
     */
    public function testNotString(): void
    {
        $validator = new PunctuationValidator();
        $result = $validator->process(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
