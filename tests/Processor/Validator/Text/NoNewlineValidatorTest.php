<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Text;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class NoNewlineValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that value not contains newlines.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\NoNewlineValidator::process()
     */
    public function testValid(): void
    {
        $validator = new NoNewlineValidator();
        $result = $validator->process('foo');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('foo', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that value contains a newline.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\NoNewlineValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\NoNewlineValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new NoNewlineValidator();
        $result = $validator->process("foo\nbar");

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Invalid.
     *
     * Test that none string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\NoNewlineValidator::process()
     */
    public function testNotString(): void
    {
        $validator = new NoNewlineValidator();
        $result = $validator->process(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
