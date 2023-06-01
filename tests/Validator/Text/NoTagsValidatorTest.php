<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use PHPUnit\Framework\TestCase;

class NoTagsValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that value does not contain tags.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NoTagsValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new NoTagsValidator();

        $this->assertTrue($validator->validate('foo', [])->isValid());
    }

    /**
     * Invalid.
     *
     * Test that value does contain tags.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NoTagsValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NoTagsValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new NoTagsValidator();

        $this->assertFalse($validator->validate('<strong>bold</strong>')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that none string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NoTagsValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new NoTagsValidator();
        $result = $validator->validate(9);

        $this->assertFalse($result->isValid());
    }
}
