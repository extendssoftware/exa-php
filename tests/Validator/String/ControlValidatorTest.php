<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class ControlValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that a string consists of control characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\ControlValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new ControlValidator();
        $result = $validator->validate("\n\r\t");

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame("\n\r\t", $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that string does not consist of control characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\ControlValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\ControlValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new ControlValidator();
        $result = $validator->validate('arf12');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Invalid.
     *
     * Test that a none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\ControlValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new ControlValidator();
        $result = $validator->validate(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
