<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\String;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class LowercaseValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that a string consists of lowercase characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\String\LowercaseValidator::process()
     */
    public function testValid(): void
    {
        $validator = new LowercaseValidator();
        $result = $validator->process('xyz');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('xyz', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that string does not consist of lowercase characters.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\String\LowercaseValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\String\LowercaseValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new LowercaseValidator();
        $result1 = $validator->process('aac123');
        $result2 = $validator->process('XyZ');

        $this->assertInstanceOf(InvalidResult::class, $result1);
        $this->assertInstanceOf(InvalidResult::class, $result2);
    }

    /**
     * Invalid.
     *
     * Test that a none-string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\String\LowercaseValidator::process()
     */
    public function testNotString(): void
    {
        $validator = new LowercaseValidator();
        $result = $validator->process(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
