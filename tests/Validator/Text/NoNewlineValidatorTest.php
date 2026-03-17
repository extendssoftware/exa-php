<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class NoNewlineValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that value not contains newlines.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NoNewlineValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new NoNewlineValidator();
        $result = $validator->validate('foo');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('foo', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that value contains a newline.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NoNewlineValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NoNewlineValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new NoNewlineValidator();
        $result = $validator->validate("foo\nbar");

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Invalid.
     *
     * Test that none string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NoNewlineValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new NoNewlineValidator();
        $result = $validator->validate(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
