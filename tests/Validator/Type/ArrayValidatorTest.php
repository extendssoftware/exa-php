<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Type;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class ArrayValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that boolean value '[]' is an array.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\ArrayValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new ArrayValidator();
        $result = $validator->validate([]);

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that int value '1' is not an array.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\ArrayValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\ArrayValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new ArrayValidator();
        $result = $validator->validate(1);

        $this->assertFalse($result->isValid());
    }
}
