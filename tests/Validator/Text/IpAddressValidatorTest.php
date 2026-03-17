<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class IpAddressValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that the IP address is valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\IpAddressValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new IpAddressValidator();
        $result = $validator->validate('0:0:0:0:0:0:0:1');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('0:0:0:0:0:0:0:1', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that a string value is invalid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\IpAddressValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\IpAddressValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new IpAddressValidator();

        $result1 = $validator->validate('foo-bar-baz');
        $result2 = $validator->validate('127.0.0.1.1');

        $this->assertInstanceOf(InvalidResult::class, $result1);
        $this->assertInstanceOf(InvalidResult::class, $result2);
    }

    /**
     * Invalid.
     *
     * Test that a none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\IpAddressValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new IpAddressValidator();
        $result = $validator->validate(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
