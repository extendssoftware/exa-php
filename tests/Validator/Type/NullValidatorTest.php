<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Type;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class NullValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that null value is valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\NullValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new NullValidator();
        $result = $validator->validate(null);

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that a non-null value is invalid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\NullValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\NullValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new NullValidator();
        $result = $validator->validate('foo');

        $this->assertFalse($result->isValid());
    }

    /**
     * Factory.
     *
     * Test that factory returns a NullValidator.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\NullValidator::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = NullValidator::factory(NullValidator::class, $serviceLocator);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
