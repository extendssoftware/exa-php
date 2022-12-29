<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class NumericValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that string consist of numeric characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\NumericValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new NumericValidator();

        $this->assertTrue($validator->validate('10002')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that string does not consist of numeric characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\NumericValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\NumericValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new NumericValidator();

        $this->assertFalse($validator->validate('1820.20')->isValid());
        $this->assertFalse($validator->validate('wsl!12')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\NumericValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new NumericValidator();
        $result = $validator->validate(9);

        $this->assertFalse($result->isValid());
    }

    /**
     * Factory.
     *
     * Test that factory returns an instanceof of ValidatorInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\NumericValidator::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = NumericValidator::factory(NumericValidator::class, $serviceLocator);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
