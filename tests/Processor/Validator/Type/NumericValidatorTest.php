<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Type;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class NumericValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that the integer value '9' is a valid integer.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\NumericValidator::process()
     */
    public function testValid(): void
    {
        $validator = new NumericValidator();
        $result = $validator->process('9.9');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('9.9', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that string value 'foo' is an valid integer.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\NumericValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\NumericValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new NumericValidator();
        $result = $validator->process('foo');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
