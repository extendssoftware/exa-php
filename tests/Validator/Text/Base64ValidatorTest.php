<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use PHPUnit\Framework\TestCase;

class Base64ValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that valid base64 value will validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\Base64Validator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\Base64Validator::validate()
     */
    public function testValid(): void
    {
        $validator = new Base64Validator();

        $result = $validator->validate('FfYkHoSJ5By549qTrEkl3ZDcJkHfJTMmrdcUPH/V+Vs='); // random_bytes(32)
        $this->assertTrue($result->isValid());

        $validator = new Base64Validator(true, true);

        $result = $validator->validate('FfYkHoSJ5By549qTrEkl3ZDcJkHfJTMmrdcUPH-V_Vs'); // random_bytes(32)
        $this->assertTrue($result->isValid());
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

        $this->assertFalse($result->isValid());
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
        $validator = new Base64Validator();
        $result = $validator->validate('FfYkHoSJ5By549qTrEkl3ZDcJkHfJTMmrdcUPH-V+Vs='); // Replaced / with -.

        $this->assertFalse($result->isValid());

        $validator = new Base64Validator(true);
        $result = $validator->validate('FfYkHoSJ5By549qTrEkl3ZDcJkHfJTMmrdcUPH/V_Vs='); // Replaced - with /.

        $this->assertFalse($result->isValid());
    }

    /**
     * Decode failed.
     *
     * Test that failed decode will return an invalid result.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\Base64Validator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\Base64Validator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\Base64Validator::getTemplates()
     */
    public function testDecodeFailed(): void
    {
        $validator = new Base64Validator();
        $result = $validator->validate('FfYkHoSJ5By549qTrEkl3ZDcJkHfJTMmrdcUPH/V+Vs=='); // Extra padding added.

        $this->assertFalse($result->isValid());

        $validator = new Base64Validator(true);
        $result = $validator->validate('FfYkHoSJ5By549qTrEkl3ZDcJkHfJTMmrdcUPH-V_Vs=='); // Extra padding added.

        $this->assertFalse($result->isValid());
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
        $validator = new Base64Validator();
        $result = $validator->validate('FfYkHoSJ5By549qTrEkl3ZDcJkHfJTMmrdcUPH/V+Vs'); // Removed padding.

        $this->assertFalse($result->isValid());

        $validator = new Base64Validator(false, true);
        $result = $validator->validate('FfYkHoSJ5By549qTrEkl3ZDcJkHfJTMmrdcUPH/V+Vs='); // Added padding.

        $this->assertFalse($result->isValid());
    }
}
