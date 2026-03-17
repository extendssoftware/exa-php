<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Type;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class NumericValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that the integer value '9' is a valid integer.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\NumericValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new NumericValidator();
        $result = $validator->validate('9.9');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('9.9', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that string value 'foo' is an valid integer.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\NumericValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\NumericValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new NumericValidator();
        $result = $validator->validate('foo');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
