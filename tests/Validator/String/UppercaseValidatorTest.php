<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
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

        $this->assertTrue($validator->validate('LMNSDO')->isValid());
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

        $this->assertFalse($validator->validate('AKLWC139')->isValid());
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

    /**
     * Factory.
     *
     * Test that factory returns an instanceof of ValidatorInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\UppercaseValidator::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = UppercaseValidator::factory(UppercaseValidator::class, $serviceLocator);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
