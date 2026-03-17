<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class Base64ValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that a valid base64 value will validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\Base64Validator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\Base64Validator::validate()
     */
    public function testValid(): void
    {
        $validator1 = new Base64Validator();
        $validator2 = new Base64Validator(true, true);

        $result1 = $validator1->validate('FfYkHoSJ5By549qTrEkl3ZDcJkHfJTMmrdcUPH/V+Vs='); // random_bytes(32)
        $result2 = $validator2->validate('FfYkHoSJ5By549qTrEkl3ZDcJkHfJTMmrdcUPH-V_Vs'); // random_bytes(32)

        $this->assertInstanceOf(ValidResult::class, $result1);
        $this->assertSame('FfYkHoSJ5By549qTrEkl3ZDcJkHfJTMmrdcUPH/V+Vs=', $result1->getValue());

        $this->assertInstanceOf(ValidResult::class, $result2);
        $this->assertSame('FfYkHoSJ5By549qTrEkl3ZDcJkHfJTMmrdcUPH-V_Vs', $result2->getValue());
    }

    /**
     * Not string.
     *
     * Test that none string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\Base64Validator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\Base64Validator::validate()
     */
    public function testNotString(): void
    {
        $validator = new Base64Validator();
        $result = $validator->validate(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Invalid characters.
     *
     * Test that invalid characters will return an invalid result.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\Base64Validator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\Base64Validator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\Base64Validator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator1 = new Base64Validator();
        $validator2 = new Base64Validator(true);

        $result1 = $validator1->validate('FfYkHoSJ5By549qTrEkl3ZDcJkHfJTMmrdcUPH-V+Vs='); // Replaced / with -.
        $result2 = $validator2->validate('FfYkHoSJ5By549qTrEkl3ZDcJkHfJTMmrdcUPH/V_Vs='); // Replaced - with /.

        $this->assertInstanceOf(InvalidResult::class, $result1);
        $this->assertInstanceOf(InvalidResult::class, $result2);
    }

    /**
     * Decode failed.
     *
     * Test that failed to decode will return an invalid result.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\Base64Validator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\Base64Validator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\Base64Validator::getTemplates()
     */
    public function testDecodeFailed(): void
    {
        $validator1 = new Base64Validator();
        $validator2 = new Base64Validator(true);

        $result1 = $validator1->validate('FfYkHoSJ5By549qTrEkl3ZDcJkHfJTMmrdcUPH/V+Vs=='); // Extra padding added.
        $result2 = $validator2->validate('FfYkHoSJ5By549qTrEkl3ZDcJkHfJTMmrdcUPH-V_Vs=='); // Extra padding added.

        $this->assertInstanceOf(InvalidResult::class, $result1);
        $this->assertInstanceOf(InvalidResult::class, $result2);
    }

    /**
     * Invalid decode encode.
     *
     * Test that different decode/encode string and initial string will return invalid result.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\Base64Validator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\Base64Validator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\Base64Validator::getTemplates()
     */
    public function testDecodeEncode(): void
    {
        $validator1 = new Base64Validator();
        $validator2 = new Base64Validator(false, true);

        $result1 = $validator1->validate('FfYkHoSJ5By549qTrEkl3ZDcJkHfJTMmrdcUPH/V+Vs'); // Removed padding.
        $result2 = $validator2->validate('FfYkHoSJ5By549qTrEkl3ZDcJkHfJTMmrdcUPH/V+Vs='); // Added padding.

        $this->assertInstanceOf(InvalidResult::class, $result1);
        $this->assertInstanceOf(InvalidResult::class, $result2);
    }
}
