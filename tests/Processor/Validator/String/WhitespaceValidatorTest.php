<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\String;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class WhitespaceValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that string consists of whitespace characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\String\WhitespaceValidator::process()
     */
    public function testValid(): void
    {
        $validator = new WhitespaceValidator();
        $result = $validator->process("\n\r\t");

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame("\n\r\t", $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that string does not consist of whitespace characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\String\WhitespaceValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\String\WhitespaceValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new WhitespaceValidator();
        $result1 = $validator->process("\narf12");
        $result2 = $validator->process('\n\r\t');

        $this->assertInstanceOf(InvalidResult::class, $result1);
        $this->assertInstanceOf(InvalidResult::class, $result2);
    }

    /**
     * Invalid.
     *
     * Test that a none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\String\WhitespaceValidator::process()
     */
    public function testNotString(): void
    {
        $validator = new WhitespaceValidator();
        $result = $validator->process(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
