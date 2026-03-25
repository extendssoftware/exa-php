<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Comparison;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class LessThanValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that int '1' is less than int '2'.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Comparison\LessThanValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Comparison\LessThanValidator::process()
     */
    public function testValid(): void
    {
        $validator = new LessThanValidator(2);
        $result = $validator->process(1);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame(1, $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that int '2' is not less than int '1'.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Comparison\LessThanValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Comparison\LessThanValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Comparison\LessThanValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new LessThanValidator(1);
        $result = $validator->process(2);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
