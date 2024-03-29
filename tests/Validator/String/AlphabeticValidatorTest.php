<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use PHPUnit\Framework\TestCase;

class AlphabeticValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that string consist of alphabetic characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\AlphabeticValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new AlphabeticValidator();

        $this->assertTrue($validator->validate('KjgWZC')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that string does not consist of alphabetic characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\AlphabeticValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\AlphabeticValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new AlphabeticValidator();

        $this->assertFalse($validator->validate('arf12')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\AlphabeticValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new AlphabeticValidator();
        $result = $validator->validate(9);

        $this->assertFalse($result->isValid());
    }
}
