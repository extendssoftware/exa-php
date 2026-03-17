<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Boolean;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
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
        $result = $validator->validate(false);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame(false, $result->getValue());
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
        $result = $validator->validate(true);

        $this->assertInstanceOf(InvalidResult::class, $result);
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

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
