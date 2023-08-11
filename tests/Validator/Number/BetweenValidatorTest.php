<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Number;

use PHPUnit\Framework\TestCase;

class BetweenValidatorTest extends TestCase
{
    /**
     * Valid inclusive values.
     *
     * @return array<array<float>>
     */
    public static function validInclusiveDataProvider(): array
    {
        return [
            [1],
            [5],
            [10],
        ];
    }

    /**
     * Invalid inclusive values.
     *
     * @return array<array<float>>
     */
    public static function invalidInclusiveDataProvider(): array
    {
        return [
            [0],
            [11],
        ];
    }

    /**
     * Invalid exclusive values.
     *
     * @return array<array<float>>
     */
    public static function invalidExclusiveDataProvider(): array
    {
        return [
            [1],
            [10],
        ];
    }

    /**
     * Valid.
     *
     * Test that values are valid.
     *
     * @param float|int $number
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::__construct()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::validate()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::getTemplates()
     * @dataProvider validInclusiveDataProvider
     */
    public function testValid(float|int $number): void
    {
        $validator = new BetweenValidator(1, 10);

        $this->assertTrue($validator->validate($number)->isValid());
    }

    /**
     * Invalid.
     *
     * Test that values are invalid.
     *
     * @param float|int $number
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::__construct()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::validate()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::getTemplates()
     * @dataProvider invalidInclusiveDataProvider
     */
    public function testInvalid(float|int $number): void
    {
        $validator = new BetweenValidator(1, 10);

        $this->assertFalse($validator->validate($number)->isValid());
    }

    /**
     * Exclusive.
     *
     * Test that bound values are invalid when inclusive is false.
     *
     * @param float|int $number
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::__construct()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::validate()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::getTemplates()
     * @dataProvider invalidExclusiveDataProvider
     */
    public function testExclusive(float|int $number): void
    {
        $validator = new BetweenValidator(1, 10, false);

        $this->assertFalse($validator->validate($number)->isValid());
    }

    /**
     * Not numeric.
     *
     * Test that value is not numeric and validate will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::validate()
     */
    public function testNotNumeric(): void
    {
        $validator = new BetweenValidator(2);
        $result = $validator->validate('a');

        $this->assertFalse($result->isValid());
    }
}
