<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

use function base64_decode;

class LengthValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that text length is in expected range and a valid result will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\LengthValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\LengthValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new LengthValidator(5, 15);
        $result = $validator->validate('foo bár báz qux');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('foo bár báz qux', $result->getValue());
    }

    /**
     * Too short.
     *
     * Test that string is too short and an invalid result will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\LengthValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\LengthValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\LengthValidator::getTemplates()
     */
    public function testTooShort(): void
    {
        $validator = new LengthValidator(5);
        $result = $validator->validate('foo');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Too long.
     *
     * Test that string is too long and an invalid result will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\LengthValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\LengthValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\LengthValidator::getTemplates()
     */
    public function testTooLong(): void
    {
        $validator = new LengthValidator(null, 10);
        $result = $validator->validate('foo bar baz');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Binary.
     *
     * Test that binary string will be validated without multibyte if a flag is set to false.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\LengthValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\LengthValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\LengthValidator::getTemplates()
     */
    public function testBinary(): void
    {
        $randomBytes = base64_decode('+QYgucK5ozyJoGuXI05TKg=='); // random_bytes(16);

        $validator1 = new LengthValidator(16, 16);
        $validator2 = new LengthValidator(1, 16, multibyte: false);

        $result1 = $validator1->validate($randomBytes);
        $result2 = $validator2->validate($randomBytes);

        $this->assertInstanceOf(InvalidResult::class, $result1);
        $this->assertInstanceOf(ValidResult::class, $result2);
        $this->assertSame($randomBytes, $result2->getValue());
    }

    /**
     * Not string.
     *
     * Test that none string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\LengthValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new LengthValidator();
        $result = $validator->validate(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
