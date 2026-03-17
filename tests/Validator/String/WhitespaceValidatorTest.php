<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class WhitespaceValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that string consists of whitespace characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\WhitespaceValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new WhitespaceValidator();
        $result = $validator->validate("\n\r\t");

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame("\n\r\t", $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that string does not consist of whitespace characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\WhitespaceValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\WhitespaceValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new WhitespaceValidator();
        $result1 = $validator->validate("\narf12");
        $result2 = $validator->validate('\n\r\t');

        $this->assertInstanceOf(InvalidResult::class, $result1);
        $this->assertInstanceOf(InvalidResult::class, $result2);
    }

    /**
     * Invalid.
     *
     * Test that a none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\WhitespaceValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new WhitespaceValidator();
        $result = $validator->validate(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
