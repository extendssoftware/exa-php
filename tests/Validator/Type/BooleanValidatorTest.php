<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Type;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
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

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame(true, $result->getValue());
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

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
