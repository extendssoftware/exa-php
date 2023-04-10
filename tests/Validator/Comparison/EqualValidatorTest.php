<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Comparison;

use PHPUnit\Framework\TestCase;

class EqualValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that string '1' is equal to int '1'.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\EqualValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\EqualValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new EqualValidator(1);
        $result = $validator->validate('1');

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that string '1' is not equal to string '2'.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\EqualValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\EqualValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\EqualValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new EqualValidator(2);
        $result = $validator->validate('1');

        $this->assertFalse($result->isValid());
    }
}
