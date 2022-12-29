<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class DigitValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that string consist of digit characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\DigitValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new DigitValidator();

        $this->assertTrue($validator->validate('10002')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that string does not consist of digit characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\DigitValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\DigitValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new DigitValidator();

        $this->assertFalse($validator->validate('1820.20')->isValid());
        $this->assertFalse($validator->validate('wsl!12')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\DigitValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new DigitValidator();
        $result = $validator->validate(9);

        $this->assertFalse($result->isValid());
    }

    /**
     * Factory.
     *
     * Test that factory returns an instanceof of ValidatorInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\DigitValidator::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = DigitValidator::factory(DigitValidator::class, $serviceLocator);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
