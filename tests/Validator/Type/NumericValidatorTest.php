<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Type;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class NumericValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that integer value '9' is a valid integer.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\NumericValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new NumericValidator();
        $result = $validator->validate('9.9');

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that string value 'foo' is an valid integer.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\NumericValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\NumericValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new NumericValidator();
        $result = $validator->validate('foo');

        $this->assertFalse($result->isValid());
    }

    /**
     * Factory.
     *
     * Test that factory returns a NumericValidator.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\NumericValidator::factory()
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
