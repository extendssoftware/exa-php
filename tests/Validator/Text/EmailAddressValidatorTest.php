<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use PHPUnit\Framework\TestCase;

class EmailAddressValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that a valid email address will validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\EmailAddressValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new EmailAddressValidator();
        $result = $validator->validate('vincent@extends.nl');

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that an invalid email address value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\EmailAddressValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\EmailAddressValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new EmailAddressValidator();
        $result = $validator->validate('foo-bar-baz');

        $this->assertFalse($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that none string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\EmailAddressValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new EmailAddressValidator();
        $result = $validator->validate(9);

        $this->assertFalse($result->isValid());
    }
}
