<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Type;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class NullValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that null value is valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\NullValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new NullValidator();
        $result = $validator->validate(null);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame(null, $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that a non-null value is invalid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\NullValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\NullValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new NullValidator();
        $result = $validator->validate('foo');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
