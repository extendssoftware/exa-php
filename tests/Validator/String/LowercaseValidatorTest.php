<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class LowercaseValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that string consist of lowercase characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\LowercaseValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new LowercaseValidator();

        $this->assertTrue($validator->validate('qiutoas')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that string does not consist of lowercase characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\LowercaseValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\LowercaseValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new LowercaseValidator();

        $this->assertFalse($validator->validate('aac123')->isValid());
        $this->assertFalse($validator->validate('QASsdks')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\LowercaseValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new LowercaseValidator();
        $result = $validator->validate(9);

        $this->assertFalse($result->isValid());
    }

    /**
     * Factory.
     *
     * Test that factory returns an instanceof of ValidatorInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\LowercaseValidator::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = LowercaseValidator::factory(LowercaseValidator::class, $serviceLocator);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
