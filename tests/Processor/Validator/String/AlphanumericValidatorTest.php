<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\String;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class AlphanumericValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that a string consists of alphanumeric characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\String\AlphanumericValidator::process()
     */
    public function testValid(): void
    {
        $validator = new AlphanumericValidator();
        $result = $validator->process('AbCd1zyZ9');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('AbCd1zyZ9', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that string does not consist of alphanumeric characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\String\AlphanumericValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\String\AlphanumericValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new AlphanumericValidator();
        $result = $validator->process('foo!#$bar');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Invalid.
     *
     * Test that a none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\String\AlphanumericValidator::process()
     */
    public function testNotString(): void
    {
        $validator = new AlphanumericValidator();
        $result = $validator->process(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
