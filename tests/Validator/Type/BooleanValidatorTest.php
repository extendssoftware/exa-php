<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Type;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class BooleanValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that boolean value 'true' is a boolean.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\BooleanValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new BooleanValidator();
        $result = $validator->validate(true);

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that int value '1' is not a boolean.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\BooleanValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\BooleanValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new BooleanValidator();
        $result = $validator->validate(1);

        $this->assertFalse($result->isValid());
    }
}
