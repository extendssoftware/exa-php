<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Comparison;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class GreaterThanValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that int '2' is greater than int '1'.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Comparison\GreaterThanValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Comparison\GreaterThanValidator::process()
     */
    public function testValid(): void
    {
        $validator = new GreaterThanValidator(1);
        $result = $validator->process(2);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame(2, $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that int '1' is not greater than int '2'.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Comparison\GreaterThanValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Comparison\GreaterThanValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Comparison\GreaterThanValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new GreaterThanValidator(1);
        $result = $validator->process(1);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
