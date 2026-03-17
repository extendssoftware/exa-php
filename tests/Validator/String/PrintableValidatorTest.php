<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class PrintableValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that string consists of visible printable characters except space.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\PrintableValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new PrintableValidator();
        $result1 = $validator->validate('arf12');
        $result2 = $validator->validate('LKA#@%.54');

        $this->assertInstanceOf(ValidResult::class, $result1);
        $this->assertSame('arf12', $result1->getValue());

        $this->assertInstanceOf(ValidResult::class, $result2);
        $this->assertSame('LKA#@%.54', $result2->getValue());
    }

    /**
     * Invalid.
     *
     * Test that string does not consist of visible printable characters except space.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\PrintableValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\PrintableValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new PrintableValidator();
        $result = $validator->validate("xyz\n\r\t");

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Invalid.
     *
     * Test that a none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\PrintableValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new PrintableValidator();
        $result = $validator->validate(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
