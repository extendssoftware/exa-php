<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Comparison;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class IdenticalValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that string '1' is identical to string '1'.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\IdenticalValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\IdenticalValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new IdenticalValidator(1);
        $result = $validator->validate(1);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame(1, $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that string '1' is not identical to int '1'.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\IdenticalValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\IdenticalValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\IdenticalValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new IdenticalValidator(1);
        $result = $validator->validate(1.0);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
