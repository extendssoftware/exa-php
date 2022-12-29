<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class AlphanumericValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that string consist of alphanumeric characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\AlphanumericValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new AlphanumericValidator();

        $this->assertTrue($validator->validate('AbCd1zyZ9')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that string does not consist of alphanumeric characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\AlphanumericValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\AlphanumericValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new AlphanumericValidator();

        $this->assertFalse($validator->validate('foo!#$bar')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\AlphanumericValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new AlphanumericValidator();
        $result = $validator->validate(9);

        $this->assertFalse($result->isValid());
    }

    /**
     * Factory.
     *
     * Test that factory returns an instanceof of ValidatorInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\AlphanumericValidator::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = AlphanumericValidator::factory(AlphanumericValidator::class, $serviceLocator);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
