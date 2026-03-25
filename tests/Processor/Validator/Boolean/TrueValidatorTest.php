<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Boolean;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class TrueValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that value equals true.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Boolean\TrueValidator::process()
     */
    public function testValid(): void
    {
        $validator = new TrueValidator();
        $result = $validator->process(true);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame(true, $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that value does not equal true.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Boolean\TrueValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Boolean\TrueValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new TrueValidator();
        $result = $validator->process(false);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Invalid.
     *
     * Test that none boolean value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Boolean\TrueValidator::process()
     */
    public function testNotBoolean(): void
    {
        $validator = new TrueValidator();
        $result = $validator->process(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
