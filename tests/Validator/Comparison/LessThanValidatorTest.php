<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Comparison;

use PHPUnit\Framework\TestCase;

class LessThanValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that int '1' is less than int '2'.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\LessThanValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\LessThanValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new LessThanValidator(2);
        $result = $validator->validate(1);

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that int '2' is not less than int '1'.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\LessThanValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\LessThanValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\LessThanValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new LessThanValidator(1);
        $result = $validator->validate(2);

        $this->assertFalse($result->isValid());
    }
}
