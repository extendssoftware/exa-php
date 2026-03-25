<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\String;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class UppercaseValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that a string consists of uppercase characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\String\UppercaseValidator::process()
     */
    public function testValid(): void
    {
        $validator = new UppercaseValidator();
        $result = $validator->process('XYZ');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('XYZ', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that string does not consist of uppercase characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\String\UppercaseValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\String\UppercaseValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new UppercaseValidator();
        $result1 = $validator->process('XYZ139');
        $result2 = $validator->process('akwSKWsm');

        $this->assertInstanceOf(InvalidResult::class, $result1);
        $this->assertInstanceOf(InvalidResult::class, $result2);
    }

    /**
     * Invalid.
     *
     * Test that a none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\String\UppercaseValidator::process()
     */
    public function testNotString(): void
    {
        $validator = new UppercaseValidator();
        $result = $validator->process(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
