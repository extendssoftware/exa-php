<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Type;

use PHPUnit\Framework\TestCase;

class NumberValidatorTest extends TestCase
{
    /**
     * Valid number values.
     *
     * @return array<array<float>>
     */
    public static function validNumberValues(): array
    {
        return [
            [8],
            [10.1],
            [-10],
            [-10.1],
        ];
    }

    /**
     * Valid.
     *
     * Test that value is a number.
     *
     * @param mixed $number
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Type\NumberValidator::validate()
     * @dataProvider validNumberValues
     */
    public function testValid(mixed $number): void
    {
        $validator = new NumberValidator();
        $result = $validator->validate($number);

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid number values.
     *
     * @return array<array<string|array<void>>>
     */
    public static function invalidNumberValues(): array
    {
        return [
            ['8'],
            ['foo'],
            ['-10'],
            ['-10.1'],
            [[]],
        ];
    }

    /**
     * Invalid.
     *
     * Test that string value 'foo' is an valid integer.
     *
     * @param mixed $number
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Type\NumberValidator::validate()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Type\NumberValidator::getTemplates()
     * @dataProvider invalidNumberValues
     */
    public function testInvalid(mixed $number): void
    {
        $validator = new NumberValidator();
        $result = $validator->validate($number);

        $this->assertFalse($result->isValid());
    }
}
