<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Type;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class StringValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that string value 'foo' is a valid string.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\StringValidator::process()
     */
    public function testValid(): void
    {
        $validator = new StringValidator();
        $result = $validator->process('foo');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('foo', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that integer value '9' is a valid string.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\StringValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\StringValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new StringValidator();
        $result = $validator->process(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
