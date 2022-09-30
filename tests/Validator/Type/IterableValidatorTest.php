<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Type;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class IterableValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that array value is iterable.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\IterableValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new IterableValidator();
        $result = $validator->validate([]);

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that string value is not iterable.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\IterableValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\IterableValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new IterableValidator();
        $result = $validator->validate('foo');

        $this->assertFalse($result->isValid());
    }

    /**
     * Factory.
     *
     * Test that factory returns a IterableValidator.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\IterableValidator::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = IterableValidator::factory(IterableValidator::class, $serviceLocator);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
