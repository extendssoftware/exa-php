<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Logical;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class NotValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that int '0' is false.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Logical\NotValidator::process()
     */
    public function testValid(): void
    {
        $validator = new NotValidator();
        $result = $validator->process(0);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame(0, $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that int '1' is not false.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Logical\NotValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Logical\NotValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new NotValidator();
        $result = $validator->process(1);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
