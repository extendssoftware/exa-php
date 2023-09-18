<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use PHPUnit\Framework\TestCase;

class NoPaddingValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that value not contains leading or trailing padding.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NoPaddingValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new NoPaddingValidator();

        $this->assertTrue($validator->validate('foo')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that value contains leading or trailing padding.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NoPaddingValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NoPaddingValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new NoPaddingValidator();

        $this->assertFalse($validator->validate("\tfoo")->isValid());
        $this->assertFalse($validator->validate('foo ')->isValid());
        $this->assertFalse($validator->validate("\nfoo\t")->isValid());
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

        $this->assertFalse($result->isValid());
    }
}
