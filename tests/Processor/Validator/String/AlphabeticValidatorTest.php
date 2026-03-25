<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\String;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class AlphabeticValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that a string consists of alphabetic characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\String\AlphabeticValidator::process()
     */
    public function testValid(): void
    {
        $validator = new AlphabeticValidator();
        $result = $validator->process('KjgWZC');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('KjgWZC', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that string does not consist of alphabetic characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\String\AlphabeticValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\String\AlphabeticValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new AlphabeticValidator();
        $result = $validator->process('arf12');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Invalid.
     *
     * Test that a none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\String\AlphabeticValidator::process()
     */
    public function testNotString(): void
    {
        $validator = new AlphabeticValidator();
        $result = $validator->process(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
