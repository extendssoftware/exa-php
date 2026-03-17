<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class DigitValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that a string consists of digit characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\DigitValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new DigitValidator();
        $result = $validator->validate('10002');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('10002', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that string does not consist of digit characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\DigitValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\DigitValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new DigitValidator();
        $result1 = $validator->validate('1820.20');
        $result2 = $validator->validate('wsl!12');

        $this->assertInstanceOf(InvalidResult::class, $result1);
        $this->assertInstanceOf(InvalidResult::class, $result2);
    }

    /**
     * Invalid.
     *
     * Test that a none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\DigitValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new DigitValidator();
        $result = $validator->validate(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
