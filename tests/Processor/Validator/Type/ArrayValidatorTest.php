<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Type;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class ArrayValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that boolean value '[]' is an array.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\ArrayValidator::process()
     */
    public function testValid(): void
    {
        $validator = new ArrayValidator();
        $result = $validator->process([]);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame([], $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that int value '1' is not an array.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\ArrayValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\ArrayValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new ArrayValidator();
        $result = $validator->process(1);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
