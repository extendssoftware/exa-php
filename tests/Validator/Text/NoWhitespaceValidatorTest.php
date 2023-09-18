<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use PHPUnit\Framework\TestCase;

class NoWhitespaceValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that value not contains whitespaces.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NoWhitespaceValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new NoWhitespaceValidator();

        $this->assertTrue($validator->validate('foo')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that value contains whitespaces.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NoWhitespaceValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NoWhitespaceValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new NoWhitespaceValidator();

        $this->assertFalse($validator->validate("foo\nbar")->isValid());
    }

    /**
     * Invalid.
     *
     * Test that none string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NoWhitespaceValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new NoWhitespaceValidator();
        $result = $validator->validate(9);

        $this->assertFalse($result->isValid());
    }
}
