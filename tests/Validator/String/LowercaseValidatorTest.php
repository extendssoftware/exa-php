<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\String;

use PHPUnit\Framework\TestCase;

class LowercaseValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that string consist of lowercase characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\LowercaseValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new LowercaseValidator();

        $this->assertTrue($validator->validate('qiutoas')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that string does not consist of lowercase characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\LowercaseValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\LowercaseValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new LowercaseValidator();

        $this->assertFalse($validator->validate('aac123')->isValid());
        $this->assertFalse($validator->validate('QASsdks')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\String\LowercaseValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new LowercaseValidator();
        $result = $validator->validate(9);

        $this->assertFalse($result->isValid());
    }
}
