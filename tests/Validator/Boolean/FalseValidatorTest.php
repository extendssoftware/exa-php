<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Boolean;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class FalseValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that value equals false.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Boolean\FalseValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new FalseValidator();

        $this->assertTrue($validator->validate(false)->isValid());
    }

    /**
     * Invalid.
     *
     * Test that value not equals false.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Boolean\FalseValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Boolean\FalseValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new FalseValidator();

        $this->assertFalse($validator->validate(true)->isValid());
    }

    /**
     * Invalid.
     *
     * Test that none boolean value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Boolean\FalseValidator::validate()
     */
    public function testNotBoolean(): void
    {
        $validator = new FalseValidator();
        $result = $validator->validate(9);

        $this->assertFalse($result->isValid());
    }
}
