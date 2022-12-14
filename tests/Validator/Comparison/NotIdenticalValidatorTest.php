<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Comparison;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class NotIdenticalValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that string '1' is not identical to int '1'.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\NotIdenticalValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\NotIdenticalValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new NotIdenticalValidator(1);
        $result = $validator->validate(1.0);

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that string '1' is identical to string '1'.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\NotIdenticalValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\NotIdenticalValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\NotIdenticalValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new NotIdenticalValidator(1);
        $result = $validator->validate(1);

        $this->assertFalse($result->isValid());
    }

    /**
     * Factory.
     *
     * Test that factory returns a NotIdenticalValidator.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\NotIdenticalValidator::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = NotIdenticalValidator::factory(ValidatorInterface::class, $serviceLocator, [
            'subject' => 5.5,
        ]);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
