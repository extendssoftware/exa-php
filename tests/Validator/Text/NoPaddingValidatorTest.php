<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class NoPaddingValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that value does not contain leading or trailing padding.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NoPaddingValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new NoPaddingValidator();
        $result = $validator->validate('foo');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('foo', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that a value contains leading or trailing padding.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NoPaddingValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NoPaddingValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new NoPaddingValidator();
        $result1 = $validator->validate("\tfoo");
        $result2 = $validator->validate('foo ');
        $result3 = $validator->validate("\nfoo\t");

        $this->assertInstanceOf(InvalidResult::class, $result1);
        $this->assertInstanceOf(InvalidResult::class, $result2);
        $this->assertInstanceOf(InvalidResult::class, $result3);
    }

    /**
     * Invalid.
     *
     * Test that none string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NoPaddingValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new NoPaddingValidator();
        $result = $validator->validate(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
