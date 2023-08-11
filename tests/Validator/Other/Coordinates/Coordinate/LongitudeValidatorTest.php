<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Other\Coordinates\Coordinate;

use PHPUnit\Framework\TestCase;

class LongitudeValidatorTest extends TestCase
{
    /**
     * Valid longitude coordinate values.
     *
     * @return array<array<float>>
     */
    public static function validLongitudeValuesProvider(): array
    {
        return [
            [-90],
            [-52.0767034],
            [0],
            [52.0767034],
            [90],
        ];
    }

    /**
     * Valid.
     *
     * Test that longitude values are valid.
     *
     * @param mixed $longitude
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Other\Coordinates\Coordinate\LongitudeValidator::validate()
     * @dataProvider validLongitudeValuesProvider
     */
    public function testValid(mixed $longitude): void
    {
        $validator = new LongitudeValidator();
        $result = $validator->validate($longitude);

        $this->assertTrue($result->isValid());
    }

    /**
     * Valid longitude coordinate values.
     *
     * @return array<array<float>>
     */
    public static function invalidLongitudeValuesProvider(): array
    {
        return [
            [-100],
            [-90.1],
            [90.1],
            [100],
        ];
    }

    /**
     * Valid.
     *
     * Test that longitude values are valid.
     *
     * @param mixed $longitude
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Other\Coordinates\Coordinate\LongitudeValidator::validate()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Other\Coordinates\Coordinate\LongitudeValidator::getTemplates()
     * @dataProvider invalidLongitudeValuesProvider
     */
    public function testInvalid(mixed $longitude): void
    {
        $validator = new LongitudeValidator();
        $result = $validator->validate($longitude);

        $this->assertFalse($result->isValid());
    }

    /**
     * Not number.
     *
     * Test that longitude must be a number.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Other\Coordinates\Coordinate\LongitudeValidator::validate()
     */
    public function testNotNumber(): void
    {
        $validator = new LongitudeValidator();
        $result = $validator->validate('foo');

        $this->assertFalse($result->isValid());
    }
}
