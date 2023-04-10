<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Other\Coordinates\Coordinate;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class LatitudeValidatorTest extends TestCase
{
    /**
     * Valid latitude coordinate values.
     *
     * @return array<array<float>>
     */
    public function validLatitudeValuesProvider(): array
    {
        return [
            [-180],
            [-52.0767034],
            [0],
            [52.0767034],
            [180],
        ];
    }

    /**
     * Valid.
     *
     * Test that latitude values are valid.
     *
     * @param mixed $latitude
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Other\Coordinates\Coordinate\LatitudeValidator::validate()
     * @dataProvider validLatitudeValuesProvider
     */
    public function testValid(mixed $latitude): void
    {
        $validator = new LatitudeValidator();
        $result = $validator->validate($latitude);

        $this->assertTrue($result->isValid());
    }

    /**
     * Valid latitude coordinate values.
     *
     * @return array<array<float>>
     */
    public function invalidLatitudeValuesProvider(): array
    {
        return [
            [-190],
            [-180.1],
            [180.1],
            [190],
        ];
    }

    /**
     * Valid.
     *
     * Test that latitude values are valid.
     *
     * @param mixed $latitude
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Other\Coordinates\Coordinate\LatitudeValidator::validate()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Other\Coordinates\Coordinate\LatitudeValidator::getTemplates()
     * @dataProvider invalidLatitudeValuesProvider
     */
    public function testInvalid(mixed $latitude): void
    {
        $validator = new LatitudeValidator();
        $result = $validator->validate($latitude);

        $this->assertFalse($result->isValid());
    }

    /**
     * Not number.
     *
     * Test that latitude must be a number.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Other\Coordinates\Coordinate\LatitudeValidator::validate()
     */
    public function testNotNumber(): void
    {
        $validator = new LatitudeValidator();
        $result = $validator->validate('foo');

        $this->assertFalse($result->isValid());
    }
}
