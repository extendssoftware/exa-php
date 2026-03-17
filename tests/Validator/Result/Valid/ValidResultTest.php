<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Result\Valid;

use PHPUnit\Framework\TestCase;

class ValidResultTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that methods return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult::isValid()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult::jsonSerialize()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult::getValue()
     */
    public function testValid(): void
    {
        $result = new ValidResult();

        $this->assertTrue($result->isValid());
        $this->assertNull($result->jsonSerialize());
        $this->assertNull($result->getValue());
    }

    /**
     * Valid with value.
     *
     * Test that methods return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult::isValid()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult::jsonSerialize()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult::getValue()
     */
    public function testValidWithValue(): void
    {
        $result = new ValidResult('foo');

        $this->assertTrue($result->isValid());
        $this->assertSame('foo', $result->jsonSerialize());
        $this->assertSame('foo', $result->getValue());
    }
}
