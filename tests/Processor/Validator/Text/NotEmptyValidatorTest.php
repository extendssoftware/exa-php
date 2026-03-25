<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Text;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class NotEmptyValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that the value is not an empty string.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\NotEmptyValidator::process()
     */
    public function testValid(): void
    {
        $validator = new NotEmptyValidator();
        $result = $validator->process('foo');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('foo', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that the value is an empty string.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\NotEmptyValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\NotEmptyValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new NotEmptyValidator();
        $result = $validator->process('');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Invalid.
     *
     * Test that none string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\NotEmptyValidator::process()
     */
    public function testNotString(): void
    {
        $validator = new NotEmptyValidator();
        $result = $validator->process(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
