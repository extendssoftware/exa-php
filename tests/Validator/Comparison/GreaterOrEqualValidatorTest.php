<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Comparison;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class GreaterOrEqualValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that int '2' is greater than int '1' and int '2' is equal to int '2'.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\GreaterOrEqualValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\GreaterOrEqualValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new GreaterOrEqualValidator(1);
        $result1 = $validator->validate(1);
        $result2 = $validator->validate(2);

        $this->assertInstanceOf(ValidResult::class, $result1);
        $this->assertSame(1, $result1->getValue());

        $this->assertInstanceOf(ValidResult::class, $result2);
        $this->assertSame(2, $result2->getValue());
    }

    /**
     * Invalid.
     *
     * Test that int '1' is not greater than or equal to int '2'.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\GreaterOrEqualValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\GreaterOrEqualValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\GreaterOrEqualValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new GreaterOrEqualValidator(2);
        $result = $validator->validate(1);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
