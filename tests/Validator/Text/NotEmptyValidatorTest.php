<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class NotEmptyValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that the value is not an empty string.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NotEmptyValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new NotEmptyValidator();
        $result = $validator->validate('foo');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('foo', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that the value is an empty string.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NotEmptyValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NotEmptyValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new NotEmptyValidator();
        $result = $validator->validate('');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Invalid.
     *
     * Test that none string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NotEmptyValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new NotEmptyValidator();
        $result = $validator->validate(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
