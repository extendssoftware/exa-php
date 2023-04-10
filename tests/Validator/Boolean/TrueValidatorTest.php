<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Boolean;

use PHPUnit\Framework\TestCase;

class TrueValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that value equals true.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Boolean\TrueValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new TrueValidator();

        $this->assertTrue($validator->validate(true)->isValid());
    }

    /**
     * Invalid.
     *
     * Test that value not equals true.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Boolean\TrueValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Boolean\TrueValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new TrueValidator();

        $this->assertFalse($validator->validate(false)->isValid());
    }

    /**
     * Invalid.
     *
     * Test that none boolean value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Boolean\TrueValidator::validate()
     */
    public function testNotBoolean(): void
    {
        $validator = new TrueValidator();
        $result = $validator->validate(9);

        $this->assertFalse($result->isValid());
    }
}
