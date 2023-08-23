<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Number;

use ExtendsSoftware\ExaPHP\Validator\Exception\TemplateNotFound;
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
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::__construct()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::validate()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::getTemplates()
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
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::__construct()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::validate()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::getTemplates()
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
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::__construct()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::validate()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::getTemplates()
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
     * @covers \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::validate()
     */
    public function testNotNumeric(): void
    {
        $validator = new BetweenValidator(2);
        $result = $validator->validate('a');

        $this->assertFalse($result->isValid());
    }

    /**
     * Allow string.
     *
     * Test that integer value as a string is allowed and will validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::validate()
     */
    public function testAllowString(): void
    {
        $validator = new BetweenValidator(1, 10, allowString: true);

        $result = $validator->validate('5');
        $this->assertTrue($result->isValid());

        $result = $validator->validate('5.5');
        $this->assertFalse($result->isValid());
    }
}
