<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class IpAddressValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that IP address is valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\IpAddressValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new IpAddressValidator();

        $this->assertTrue($validator->validate('0:0:0:0:0:0:0:1')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that string value is invalid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\IpAddressValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\IpAddressValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new IpAddressValidator();

        $this->assertFalse($validator->validate('foo-bar-baz')->isValid());
        $this->assertFalse($validator->validate('127.0.0.1.1')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\IpAddressValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new IpAddressValidator();
        $result = $validator->validate(9);

        $this->assertFalse($result->isValid());
    }
}
