<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class PrintableValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that string consist of visible printable characters except space.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\PrintableValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new PrintableValidator();

        $this->assertTrue($validator->validate('arf12')->isValid());
        $this->assertTrue($validator->validate('LKA#@%.54')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that string does not consist of visible printable characters except space.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\PrintableValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\PrintableValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new PrintableValidator();

        $this->assertFalse($validator->validate("asdf\n\r\t")->isValid());
    }

    /**
     * Invalid.
     *
     * Test that none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\PrintableValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new PrintableValidator();
        $result = $validator->validate(9);

        $this->assertFalse($result->isValid());
    }
}
