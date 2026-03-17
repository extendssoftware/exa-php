<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
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
        $result = $validator->validate('foo');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('foo', $result->getValue());
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
        $result = $validator->validate('<strong>bold</strong>');

        $this->assertInstanceOf(InvalidResult::class, $result);
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

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
