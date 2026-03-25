<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Text;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class IpAddressValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that the IP address is valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\IpAddressValidator::process()
     */
    public function testValid(): void
    {
        $validator = new IpAddressValidator();
        $result = $validator->process('0:0:0:0:0:0:0:1');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('0:0:0:0:0:0:0:1', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that a string value is invalid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\IpAddressValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\IpAddressValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new IpAddressValidator();

        $result1 = $validator->process('foo-bar-baz');
        $result2 = $validator->process('127.0.0.1.1');

        $this->assertInstanceOf(InvalidResult::class, $result1);
        $this->assertInstanceOf(InvalidResult::class, $result2);
    }

    /**
     * Invalid.
     *
     * Test that a none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\IpAddressValidator::process()
     */
    public function testNotString(): void
    {
        $validator = new IpAddressValidator();
        $result = $validator->process(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
