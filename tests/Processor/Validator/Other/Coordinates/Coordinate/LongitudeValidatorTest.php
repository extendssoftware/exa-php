<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Other\Coordinates\Coordinate;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
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
     *
     * @covers       \ExtendsSoftware\ExaPHP\Processor\Validator\Other\Coordinates\Coordinate\LongitudeValidator::process()
     * @dataProvider validLongitudeValuesProvider
     */
    public function testValid(mixed $longitude): void
    {
        $validator = new LongitudeValidator();
        $result = $validator->process($longitude);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame($longitude, $result->getValue());
    }

    /**
     * Valid.
     *
     * Test that longitude values are valid.
     *
     * @param mixed $longitude
     *
     * @covers       \ExtendsSoftware\ExaPHP\Processor\Validator\Other\Coordinates\Coordinate\LongitudeValidator::process()
     * @covers       \ExtendsSoftware\ExaPHP\Processor\Validator\Other\Coordinates\Coordinate\LongitudeValidator::getTemplates()
     * @dataProvider invalidLongitudeValuesProvider
     */
    public function testInvalid(mixed $longitude): void
    {
        $validator = new LongitudeValidator();
        $result = $validator->process($longitude);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Not number.
     *
     * Test that longitude must be a number.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Other\Coordinates\Coordinate\LongitudeValidator::process()
     */
    public function testNotNumber(): void
    {
        $validator = new LongitudeValidator();
        $result = $validator->process('foo');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
