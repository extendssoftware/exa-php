<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use PHPUnit\Framework\TestCase;

class DateTimeValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that value '2019-11-10 18:39:59' is a valid date time notation.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\DateTimeValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\DateTimeValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new DateTimeValidator('Y-m-d H:i:s');
        $result = $validator->validate('2019-11-10 18:39:59');

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that string value '2019-11-10 18:39:59' is a valid date only notation.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\DateTimeValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\DateTimeValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\DateTimeValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new DateTimeValidator('Y-m-d');
        $result = $validator->validate('2019-11-10 18:39:59');

        $this->assertFalse($result->isValid());
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

        $this->assertFalse($result->isValid());
    }
}
