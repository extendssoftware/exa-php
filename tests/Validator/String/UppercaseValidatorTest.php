<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use PHPUnit\Framework\TestCase;

class UppercaseValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that string consist of uppercase characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\UppercaseValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new UppercaseValidator();

        $this->assertTrue($validator->validate('XYZ')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that string does not consist of uppercase characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\UppercaseValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\UppercaseValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new UppercaseValidator();

        $this->assertFalse($validator->validate('XYZ139')->isValid());
        $this->assertFalse($validator->validate('akwSKWsm')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\UppercaseValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new UppercaseValidator();
        $result = $validator->validate(9);

        $this->assertFalse($result->isValid());
    }
}
