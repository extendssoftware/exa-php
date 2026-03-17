<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Collection;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class ConstraintValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that all values will be valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\ConstraintValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\ConstraintValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new ConstraintValidator([1, 2, 3]);
        $result = $validator->validate([
            1,
            3,
        ]);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame([
            1,
            3,
        ], $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that not all values are valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\ConstraintValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\ConstraintValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\ConstraintValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new ConstraintValidator([1, 4, 5, 6]);
        $result = $validator->validate([
            1,
            3,
            8,
        ]);

        $this->assertInstanceOf(InvalidResult::class, $result);
        $this->assertEquals([
            'code' => 'notAllowedValues',
            'message' => 'Values {{not_allowed}} are not allowed in the array.',
            'parameters' => (object)[
                'not_allowed' => [
                    3,
                    8,
                ],
            ],
        ], $result->jsonSerialize());
    }

    /**
     * Not iterable.
     *
     * Test that validator will be invalid when the value is not iterable.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\ConstraintValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\ConstraintValidator::validate()
     */
    public function testNotIterable(): void
    {
        $validator = new ConstraintValidator([1, 2, 3]);
        $result = $validator->validate(3);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
