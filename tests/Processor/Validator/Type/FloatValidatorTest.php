<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Type;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class FloatValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that float value '9.0' is a float.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\FloatValidator::process()
     */
    public function testValid(): void
    {
        $validator = new FloatValidator();
        $result = $validator->process(9.1);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame(9.1, $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that int value '9' is not a float.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\FloatValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\FloatValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new FloatValidator();
        $result = $validator->process(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
