<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Comparison;

use PHPUnit\Framework\TestCase;

class GreaterThanValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that int '2' is greater than int '1'.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\GreaterThanValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\GreaterThanValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new GreaterThanValidator(1);
        $result = $validator->validate(2);

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that int '1' is not greater than int '2'.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\GreaterThanValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\GreaterThanValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\GreaterThanValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new GreaterThanValidator(1);
        $result = $validator->validate(1);

        $this->assertFalse($result->isValid());
    }
}
