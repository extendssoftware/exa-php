<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Other;

use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class ValidValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that any value is valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Other\ValidValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new ValidValidator();

        $result1 = $validator->validate('foo');
        $this->assertInstanceOf(ValidResult::class, $result1);
        $this->assertSame('foo', $result1->getValue());

        $result2 = $validator->validate(9);
        $this->assertInstanceOf(ValidResult::class, $result2);
        $this->assertSame(9, $result2->getValue());

        $result3 = $validator->validate(false);
        $this->assertInstanceOf(ValidResult::class, $result3);
        $this->assertSame(false, $result3->getValue());
    }
}
