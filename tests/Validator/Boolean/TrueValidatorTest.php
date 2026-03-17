<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Boolean;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class TrueValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that value equals true.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Boolean\TrueValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new TrueValidator();
        $result = $validator->validate(true);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame(true, $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that value does not equal true.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Boolean\TrueValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Boolean\TrueValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new TrueValidator();
        $result = $validator->validate(false);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Invalid.
     *
     * Test that none boolean value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Boolean\TrueValidator::validate()
     */
    public function testNotBoolean(): void
    {
        $validator = new TrueValidator();
        $result = $validator->validate(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
