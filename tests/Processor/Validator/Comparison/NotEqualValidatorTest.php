<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Comparison;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class NotEqualValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that string '1' is not equal to string '2'.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Comparison\NotEqualValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Comparison\NotEqualValidator::process()
     */
    public function testValid(): void
    {
        $validator = new NotEqualValidator(1);
        $result = $validator->process('2');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('2', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that string '1' is equal to int '1'.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Comparison\NotEqualValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Comparison\NotEqualValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Comparison\NotEqualValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new NotEqualValidator(1);
        $result = $validator->process('1');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
