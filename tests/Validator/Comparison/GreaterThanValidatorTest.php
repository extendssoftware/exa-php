<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Comparison;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class GreaterThanValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that int '2' is greater than int '1'.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\GreaterThanValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\GreaterThanValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new GreaterThanValidator(1);
        $result = $validator->validate(2);

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that int '1' is not greater than int '2'.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\GreaterThanValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\GreaterThanValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\GreaterThanValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new GreaterThanValidator(1);
        $result = $validator->validate(1);

        $this->assertFalse($result->isValid());
    }

    /**
     * Factory.
     *
     * Test that factory returns a GreaterOrEqualValidator.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\GreaterThanValidator::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = GreaterThanValidator::factory(ValidatorInterface::class, $serviceLocator, [
            'subject' => 5.5,
        ]);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
