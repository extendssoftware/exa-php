<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Comparison;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class IdenticalValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that string '1' is identical to string '1'.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Comparison\IdenticalValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Comparison\IdenticalValidator::process()
     */
    public function testValid(): void
    {
        $validator = new IdenticalValidator(1);
        $result = $validator->process(1);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame(1, $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that string '1' is not identical to int '1'.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Comparison\IdenticalValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Comparison\IdenticalValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Comparison\IdenticalValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new IdenticalValidator(1);
        $result = $validator->process(1.0);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
