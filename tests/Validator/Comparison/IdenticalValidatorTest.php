<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Comparison;

use PHPUnit\Framework\TestCase;

class IdenticalValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that string '1' is identical to string '1'.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\IdenticalValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\IdenticalValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new IdenticalValidator(1);
        $result = $validator->validate(1);

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that string '1' is not identical to int '1'.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\IdenticalValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\IdenticalValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Comparison\IdenticalValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new IdenticalValidator(1);
        $result = $validator->validate(1.0);

        $this->assertFalse($result->isValid());
    }
}
