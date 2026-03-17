<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class JsonValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that a JSON string is valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\JsonValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new JsonValidator();
        $result = $validator->validate('{"foo":"bar"}');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('{"foo":"bar"}', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that a string value is invalid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\JsonValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\JsonValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new JsonValidator();
        $result = $validator->validate('{"foo":"bar}');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Invalid.
     *
     * Test that a none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\JsonValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new JsonValidator();
        $result = $validator->validate(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
