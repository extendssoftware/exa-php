<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Other\Coordinates;

use PHPUnit\Framework\TestCase;
use stdClass;

class CoordinatesValidatorTest extends TestCase
{
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
        $result = $validator->validate((object)[
            'latitude' => 52.0767034,
            'longitude' => 5.4777887,
        ]);

        $this->assertTrue($result->isValid());
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
        $result = $validator->validate((object)[
            'lat' => 52.0767034,
            'lng' => 5.4777887,
        ]);

        $this->assertTrue($result->isValid());
    }

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
     * Invalid coordinates object.
     *
     * Test that coordinates object is invalid and an invalid result will be returned.
     *
     * @param mixed $coordinates
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Other\Coordinates\CoordinatesValidator::validate()
     * @dataProvider invalidCoordinatesObjectProvider
     */
    public function testInvalidCoordinatesObject(mixed $coordinates): void
    {
        $validator = new CoordinatesValidator();
        $result = $validator->validate($coordinates);

        $this->assertFalse($result->isValid());
    }
}
