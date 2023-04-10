<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use PHPUnit\Framework\TestCase;

class LengthValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that text length is in expected range and a valid result will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\LengthValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\LengthValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new LengthValidator(5, 15);
        $result = $validator->validate('foo bár báz qux');

        $this->assertTrue($result->isValid());
    }

    /**
     * Too short.
     *
     * Test that string is too short and an invalid result will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\LengthValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\LengthValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\LengthValidator::getTemplates()
     */
    public function testTooShort(): void
    {
        $validator = new LengthValidator(5);
        $result = $validator->validate('foo');

        $this->assertFalse($result->isValid());
    }

    /**
     * Too long.
     *
     * Test that string is too long and an invalid result will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\LengthValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\LengthValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\LengthValidator::getTemplates()
     */
    public function testTooLong(): void
    {
        $validator = new LengthValidator(null, 10);
        $result = $validator->validate('foo bar baz');

        $this->assertFalse($result->isValid());
    }

    /**
     * Not string.
     *
     * Test that none string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\LengthValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new LengthValidator();
        $result = $validator->validate(9);

        $this->assertFalse($result->isValid());
    }
}
