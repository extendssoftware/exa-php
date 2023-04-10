<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use PHPUnit\Framework\TestCase;

class WhitespaceValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that string consist of whitespace characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\WhitespaceValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new WhitespaceValidator();

        $this->assertTrue($validator->validate("\n\r\t")->isValid());
    }

    /**
     * Invalid.
     *
     * Test that string does not consist of whitespace characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\WhitespaceValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\WhitespaceValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new WhitespaceValidator();

        $this->assertFalse($validator->validate("\narf12")->isValid());
        $this->assertFalse($validator->validate('\n\r\t')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\WhitespaceValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new WhitespaceValidator();
        $result = $validator->validate(9);

        $this->assertFalse($result->isValid());
    }
}
