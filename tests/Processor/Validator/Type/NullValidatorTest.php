<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Type;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class NullValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that null value is valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\NullValidator::process()
     */
    public function testValid(): void
    {
        $validator = new NullValidator();
        $result = $validator->process(null);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame(null, $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that a non-null value is invalid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\NullValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\NullValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new NullValidator();
        $result = $validator->process('foo');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
