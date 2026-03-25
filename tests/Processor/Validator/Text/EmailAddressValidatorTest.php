<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Text;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class EmailAddressValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that a valid email address will validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\EmailAddressValidator::process()
     */
    public function testValid(): void
    {
        $validator = new EmailAddressValidator();
        $result = $validator->process('vincent@extends.nl');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('vincent@extends.nl', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that an invalid email address value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\EmailAddressValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\EmailAddressValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new EmailAddressValidator();
        $result = $validator->process('foo-bar-baz');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Invalid.
     *
     * Test that none string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\EmailAddressValidator::process()
     */
    public function testNotString(): void
    {
        $validator = new EmailAddressValidator();
        $result = $validator->process(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
