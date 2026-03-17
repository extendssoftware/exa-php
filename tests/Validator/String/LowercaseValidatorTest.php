<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class LowercaseValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that a string consists of lowercase characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\LowercaseValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new LowercaseValidator();
        $result = $validator->validate('xyz');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('xyz', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that string does not consist of lowercase characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\LowercaseValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\LowercaseValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new LowercaseValidator();
        $result1 = $validator->validate('aac123');
        $result2 = $validator->validate('XyZ');

        $this->assertInstanceOf(InvalidResult::class, $result1);
        $this->assertInstanceOf(InvalidResult::class, $result2);
    }

    /**
     * Invalid.
     *
     * Test that a none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\LowercaseValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new LowercaseValidator();
        $result = $validator->validate(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
