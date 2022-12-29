<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
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

    /**
     * Factory.
     *
     * Test that factory returns an instanceof of ValidatorInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\AlphabeticValidator::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = AlphabeticValidator::factory(AlphabeticValidator::class, $serviceLocator);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
