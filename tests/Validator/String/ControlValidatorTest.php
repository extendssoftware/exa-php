<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class ControlValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that string consist of control characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\ControlValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new ControlValidator();

        $this->assertTrue($validator->validate("\n\r\t")->isValid());
    }

    /**
     * Invalid.
     *
     * Test that string does not consist of control characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\ControlValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\ControlValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new ControlValidator();

        $this->assertFalse($validator->validate('arf12')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\ControlValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new ControlValidator();
        $result = $validator->validate(9);

        $this->assertFalse($result->isValid());
    }

    /**
     * Factory.
     *
     * Test that factory returns an instanceof of ValidatorInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\ControlValidator::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = ControlValidator::factory(ControlValidator::class, $serviceLocator);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
