<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use PHPUnit\Framework\TestCase;

class JsonValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that JSON string is valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\JsonValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new JsonValidator();

        $this->assertTrue($validator->validate('{"foo":"bar"}')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that string value is invalid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\JsonValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\JsonValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new JsonValidator();

        $this->assertFalse($validator->validate('{"foo":"bar}')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\JsonValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new JsonValidator();
        $result = $validator->validate(9);

        $this->assertFalse($result->isValid());
    }
}
