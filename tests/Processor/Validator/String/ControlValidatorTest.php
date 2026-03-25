<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\String;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class ControlValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that a string consists of control characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\String\ControlValidator::process()
     */
    public function testValid(): void
    {
        $validator = new ControlValidator();
        $result = $validator->process("\n\r\t");

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame("\n\r\t", $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that string does not consist of control characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\String\ControlValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\String\ControlValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new ControlValidator();
        $result = $validator->process('arf12');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Invalid.
     *
     * Test that a none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\String\ControlValidator::process()
     */
    public function testNotString(): void
    {
        $validator = new ControlValidator();
        $result = $validator->process(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
