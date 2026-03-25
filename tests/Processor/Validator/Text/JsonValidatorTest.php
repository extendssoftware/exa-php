<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Text;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class JsonValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that a JSON string is valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\JsonValidator::process()
     */
    public function testValid(): void
    {
        $validator = new JsonValidator();
        $result = $validator->process('{"foo":"bar"}');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('{"foo":"bar"}', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that a string value is invalid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\JsonValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\JsonValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new JsonValidator();
        $result = $validator->process('{"foo":"bar}');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Invalid.
     *
     * Test that a none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\JsonValidator::process()
     */
    public function testNotString(): void
    {
        $validator = new JsonValidator();
        $result = $validator->process(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
