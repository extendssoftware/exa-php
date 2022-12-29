<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class PunctuationValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that string consist of punctuation characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\PunctuationValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new PunctuationValidator();

        $this->assertTrue($validator->validate('*&$()')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that string does not consist of punctuation characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\PunctuationValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\PunctuationValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new PunctuationValidator();

        $this->assertFalse($validator->validate('ABasdk!@!$#')->isValid());
        $this->assertFalse($validator->validate('!@ # $')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\PunctuationValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new PunctuationValidator();
        $result = $validator->validate(9);

        $this->assertFalse($result->isValid());
    }

    /**
     * Factory.
     *
     * Test that factory returns an instanceof of ValidatorInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\PunctuationValidator::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = PunctuationValidator::factory(PunctuationValidator::class, $serviceLocator);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
