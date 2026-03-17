<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class UppercaseValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that a string consists of uppercase characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\UppercaseValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new UppercaseValidator();
        $result = $validator->validate('XYZ');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('XYZ', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that string does not consist of uppercase characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\UppercaseValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\UppercaseValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new UppercaseValidator();
        $result1 = $validator->validate('XYZ139');
        $result2 = $validator->validate('akwSKWsm');

        $this->assertInstanceOf(InvalidResult::class, $result1);
        $this->assertInstanceOf(InvalidResult::class, $result2);
    }

    /**
     * Invalid.
     *
     * Test that a none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\UppercaseValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new UppercaseValidator();
        $result = $validator->validate(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
