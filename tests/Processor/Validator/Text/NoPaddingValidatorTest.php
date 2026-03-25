<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Text;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class NoPaddingValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that value does not contain leading or trailing padding.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\NoPaddingValidator::process()
     */
    public function testValid(): void
    {
        $validator = new NoPaddingValidator();
        $result = $validator->process('foo');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('foo', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that a value contains leading or trailing padding.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\NoPaddingValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\NoPaddingValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new NoPaddingValidator();
        $result1 = $validator->process("\tfoo");
        $result2 = $validator->process('foo ');
        $result3 = $validator->process("\nfoo\t");

        $this->assertInstanceOf(InvalidResult::class, $result1);
        $this->assertInstanceOf(InvalidResult::class, $result2);
        $this->assertInstanceOf(InvalidResult::class, $result3);
    }

    /**
     * Invalid.
     *
     * Test that none string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\NoPaddingValidator::process()
     */
    public function testNotString(): void
    {
        $validator = new NoPaddingValidator();
        $result = $validator->process(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
