<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Other\Coordinates;

use ExtendsSoftware\ExaPHP\Validator\Result\Container\ContainerResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;
use stdClass;

class CoordinatesValidatorTest extends TestCase
{
    /**
     * Invalid coordinates object provider.
     *
     * @return array<array<object>>
     */
    public static function invalidCoordinatesObjectProvider(): array
    {
        return [
            [
                (object)[
                    'latitude' => 52.0767034,
                ],
            ],
            [
                (object)[
                    'longitude' => 5.4777887,
                ],
            ],
            [
                new stdClass(),
            ],
            [
                (object)[
                    'latitude' => 'foo',
                    'longitude' => 'bar',
                ],
            ],
        ];
    }

    /**
     * Valid.
     *
     * Test that latitude and longitude are valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Other\Coordinates\CoordinatesValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new CoordinatesValidator();
        $result = $validator->validate(
            (object)[
                'latitude' => 52.0767034,
                'longitude' => 5.4777887,
            ],
        );

        $this->assertInstanceOf(ContainerResult::class, $result);
        $this->assertTrue($result->isValid());

        $results = $result->getResults();
        $latitude = $results['latitude'];
        $longitude = $results['longitude'];

        $this->assertInstanceOf(ValidResult::class, $latitude);
        $this->assertSame(52.0767034, $latitude->getValue());

        $this->assertInstanceOf(ValidResult::class, $longitude);
        $this->assertSame(5.4777887, $longitude->getValue());
    }

    /**
     * Custom keys.
     *
     * Test that latitude and longitude custom keys are valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Other\Coordinates\CoordinatesValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Other\Coordinates\CoordinatesValidator::validate()
     */
    public function testCustomKeys(): void
    {
        $validator = new CoordinatesValidator('lat', 'lng');
        $result = $validator->validate(
            (object)[
                'lat' => 52.0767034,
                'lng' => 5.4777887,
            ],
        );

        $this->assertInstanceOf(ContainerResult::class, $result);
        $this->assertTrue($result->isValid());

        $results = $result->getResults();
        $latitude = $results['lat'];
        $longitude = $results['lng'];

        $this->assertInstanceOf(ValidResult::class, $latitude);
        $this->assertSame(52.0767034, $latitude->getValue());

        $this->assertInstanceOf(ValidResult::class, $longitude);
        $this->assertSame(5.4777887, $longitude->getValue());
    }

    /**
     * Invalid coordinates object.
     *
     * Test that the coordinates object is invalid and an invalid result will be returned.
     *
     * @param mixed $coordinates
     *
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Other\Coordinates\CoordinatesValidator::validate()
     * @dataProvider invalidCoordinatesObjectProvider
     */
    public function testInvalidCoordinatesObject(mixed $coordinates): void
    {
        $validator = new CoordinatesValidator();
        $result = $validator->validate($coordinates);

        $this->assertInstanceOf(ContainerResult::class, $result);
        $this->assertFalse($result->isValid());
    }
}
