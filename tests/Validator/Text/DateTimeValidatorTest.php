<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class DateTimeValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that the value '2019-11-10 18:39:59' is a valid date time notation.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\DateTimeValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\DateTimeValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new DateTimeValidator('Y-m-d H:i:s');
        $result = $validator->validate('2019-11-10 18:39:59');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('2019-11-10 18:39:59', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that the string value '2019-11-10 18:39:59' is a valid date-only notation.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\DateTimeValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\DateTimeValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\DateTimeValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new DateTimeValidator('Y-m-d');
        $result = $validator->validate('2019-11-10 18:39:59');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Invalid.
     *
     * Test that none string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\DateTimeValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\DateTimeValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new DateTimeValidator();
        $result = $validator->validate(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
