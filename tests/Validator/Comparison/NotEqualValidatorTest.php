<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Comparison;

use PHPUnit\Framework\TestCase;

class NotEqualValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that string '1' is not equal to string '2'.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\NotEqualValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\NotEqualValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new NotEqualValidator(1);
        $result = $validator->validate('2');

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that string '1' is equal to int '1'.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\NotEqualValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\NotEqualValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\NotEqualValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new NotEqualValidator(1);
        $result = $validator->validate('1');

        $this->assertFalse($result->isValid());
    }
}
