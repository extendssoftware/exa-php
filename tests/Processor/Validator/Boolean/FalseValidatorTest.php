<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Boolean;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class FalseValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that value equals false.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Boolean\FalseValidator::process()
     */
    public function testValid(): void
    {
        $validator = new FalseValidator();
        $result = $validator->process(false);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame(false, $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that value not equals false.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Boolean\FalseValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Boolean\FalseValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new FalseValidator();
        $result = $validator->process(true);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Invalid.
     *
     * Test that none boolean value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Boolean\FalseValidator::process()
     */
    public function testNotBoolean(): void
    {
        $validator = new FalseValidator();
        $result = $validator->process(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
