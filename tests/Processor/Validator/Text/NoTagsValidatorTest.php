<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Text;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class NoTagsValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that value does not contain tags.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\NoTagsValidator::process()
     */
    public function testValid(): void
    {
        $validator = new NoTagsValidator();
        $result = $validator->process('foo');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('foo', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that value does contain tags.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\NoTagsValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\NoTagsValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new NoTagsValidator();
        $result = $validator->process('<strong>bold</strong>');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Invalid.
     *
     * Test that none string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\NoTagsValidator::process()
     */
    public function testNotString(): void
    {
        $validator = new NoTagsValidator();
        $result = $validator->process(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
