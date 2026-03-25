<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Comparison;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class EqualValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that the string '1' is equal to int '1'.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Comparison\EqualValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Comparison\EqualValidator::process()
     */
    public function testValid(): void
    {
        $validator = new EqualValidator(1);
        $result = $validator->process('1');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('1', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that string '1' is not equal to string '2'.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Comparison\EqualValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Comparison\EqualValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Comparison\EqualValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new EqualValidator(2);
        $result = $validator->process('1');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
