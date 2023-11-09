<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Collection;

use PHPUnit\Framework\TestCase;

class InArrayValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that value exist in array and a valid result will be returned.
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

        $this->assertTrue($result->isValid());
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

        $this->assertTrue($validator->validate('1')->isValid());
        $this->assertTrue($validator->validate(2)->isValid());
        $this->assertTrue($validator->validate(3.0)->isValid());

        $this->assertFalse($validator->validate(1)->isValid());
        $this->assertFalse($validator->validate('2')->isValid());
        $this->assertFalse($validator->validate(3)->isValid());
    }

    /**
     * Not in array.
     *
     * Test that value not exist in array and an invalid result will be returned.
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

        $this->assertFalse($result->isValid());
        $this->assertEquals([
            'code' => 'notInArray',
            'message' => 'Value {{value}} is not allowed in array, only {{values}} are allowed.',
            'parameters' => [
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
