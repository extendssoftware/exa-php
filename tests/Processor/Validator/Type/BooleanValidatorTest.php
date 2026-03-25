<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Type;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class BooleanValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that boolean value 'true' is a boolean.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\BooleanValidator::process()
     */
    public function testValid(): void
    {
        $validator = new BooleanValidator();
        $result = $validator->process(true);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame(true, $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that int value '1' is not a boolean.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\BooleanValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\BooleanValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new BooleanValidator();
        $result = $validator->process(1);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
