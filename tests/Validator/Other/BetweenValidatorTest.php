<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Other;

use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Validator\Text\DateTimeValidator;
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
     * @param int $integer
     *
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Other\BetweenValidator::__construct()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Other\BetweenValidator::validate()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Other\BetweenValidator::getTemplates()
     * @dataProvider validInclusiveDataProvider
     */
    public function testValid(int $integer): void
    {
        $validator = new BetweenValidator(1, 10);

        $this->assertTrue($validator->validate($integer)->isValid());
    }

    /**
     * Invalid.
     *
     * Test that values are invalid.
     *
     * @param int $integer
     *
     * @throws TemplateNotFound
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Other\BetweenValidator::__construct()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Other\BetweenValidator::validate()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Other\BetweenValidator::getTemplates()
     * @dataProvider invalidInclusiveDataProvider
     */
    public function testInvalid(int $integer): void
    {
        $validator = new BetweenValidator(1, 10);

        $this->assertFalse($validator->validate($integer)->isValid());
    }

    /**
     * Exclusive.
     *
     * Test that bound values are invalid when inclusive is false.
     *
     * @param int $integer
     *
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Other\BetweenValidator::__construct()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Other\BetweenValidator::validate()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Other\BetweenValidator::getTemplates()
     * @dataProvider invalidExclusiveDataProvider
     */
    public function testExclusive(int $integer): void
    {
        $validator = new BetweenValidator(1, 10, false);

        $this->assertFalse($validator->validate($integer)->isValid());
    }

    /**
     * Not numeric.
     *
     * Test that value is not numeric and validate will not validate.
     *
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Other\BetweenValidator::__construct()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Other\BetweenValidator::validate()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Other\BetweenValidator::getTemplates()
     */
    public function testNotNumeric(): void
    {
        $validator = new BetweenValidator(2);
        $result = $validator->validate('a');

        $this->assertFalse($result->isValid());
    }

    /**
     * Internal validator.
     *
     * Test that internal validator will validate values and allow for different type of values.
     *
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Other\BetweenValidator::__construct()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Other\BetweenValidator::validate()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Other\BetweenValidator::getTemplates()
     */
    public function testInternalValidator(): void
    {
        $validator = new BetweenValidator('2023-01-01', '2023-12-31', validator: new DateTimeValidator('Y-m-d'));

        $this->assertTrue($validator->validate('2023-10-10')->isValid());
        $this->assertFalse($validator->validate('2022-10-10')->isValid());
        $this->assertFalse($validator->validate('2024-10-10')->isValid());
    }
}
