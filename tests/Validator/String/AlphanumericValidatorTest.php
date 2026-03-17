<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class AlphanumericValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that a string consists of alphanumeric characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\AlphanumericValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new AlphanumericValidator();
        $result = $validator->validate('AbCd1zyZ9');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('AbCd1zyZ9', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that string does not consist of alphanumeric characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\AlphanumericValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\AlphanumericValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new AlphanumericValidator();
        $result = $validator->validate('foo!#$bar');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Invalid.
     *
     * Test that a none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\AlphanumericValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new AlphanumericValidator();
        $result = $validator->validate(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
