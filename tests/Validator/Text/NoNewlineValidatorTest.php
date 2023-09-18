<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use PHPUnit\Framework\TestCase;

class NoNewlineValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that value not contain newlines.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NoNewlineValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new NoNewlineValidator();

        $this->assertTrue($validator->validate('foo')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that value contains newline.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NoNewlineValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NoNewlineValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new NoNewlineValidator();

        $this->assertFalse($validator->validate("foo\nbar")->isValid());
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

        $this->assertFalse($result->isValid());
    }
}
