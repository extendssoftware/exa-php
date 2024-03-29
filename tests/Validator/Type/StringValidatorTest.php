<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Type;

use PHPUnit\Framework\TestCase;

class StringValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that string value 'foo' is a valid string.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\StringValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new StringValidator();
        $result = $validator->validate('foo');

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that integer value '9' is a valid string.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\StringValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\StringValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new StringValidator();
        $result = $validator->validate(9);

        $this->assertFalse($result->isValid());
    }
}
