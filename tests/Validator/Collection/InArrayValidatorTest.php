<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Collection;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class InArrayValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that value exists in an array and a valid result will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\InArrayValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\InArrayValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new InArrayValidator([
            'foo',
            'bar',
            'baz',
        ]);
        $result = $validator->validate('foo');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('foo', $result->getValue());
    }

    /**
     * Strictness.
     *
     * Test that value will be validated with type strictness.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\InArrayValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\InArrayValidator::validate()
     */
    public function testStrictness(): void
    {
        $validator = new InArrayValidator([
            '1',
            2,
            3.0,
        ], true);

        $result1 = $validator->validate('1');
        $result2 = $validator->validate(2);
        $result3 = $validator->validate(3.0);
        $result4 = $validator->validate(1);
        $result5 = $validator->validate('2');
        $result6 = $validator->validate(3);

        $this->assertInstanceOf(ValidResult::class, $result1);
        $this->assertSame('1', $result1->getValue());

        $this->assertInstanceOf(ValidResult::class, $result2);
        $this->assertSame(2, $result2->getValue());

        $this->assertInstanceOf(ValidResult::class, $result3);
        $this->assertSame(3.0, $result3->getValue());

        $this->assertInstanceOf(InvalidResult::class, $result4);
        $this->assertInstanceOf(InvalidResult::class, $result5);
        $this->assertInstanceOf(InvalidResult::class, $result6);
    }

    /**
     * Not in array.
     *
     * Test that value does not exist in an array and an invalid result will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\InArrayValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\InArrayValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\InArrayValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new InArrayValidator([
            'foo',
            'bar',
            'baz',
        ]);
        $result = $validator->validate('qux');

        $this->assertInstanceOf(InvalidResult::class, $result);
        $this->assertEquals([
            'code' => 'notInArray',
            'message' => 'Value {{value}} is not allowed in array, only {{values}} are allowed.',
            'parameters' => (object)[
                'value' => 'qux',
                'array' => [
                    'foo',
                    'bar',
                    'baz',
                ],
            ],
        ], $result->jsonSerialize());
    }
}
