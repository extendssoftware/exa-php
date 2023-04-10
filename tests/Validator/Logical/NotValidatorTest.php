<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Logical;

use PHPUnit\Framework\TestCase;

class NotValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that int '0' is false.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Logical\NotValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new NotValidator();
        $result = $validator->validate(0);

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that int '1' is not false.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Logical\NotValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Logical\NotValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new NotValidator();
        $result = $validator->validate(1);

        $this->assertFalse($result->isValid());
    }
}
