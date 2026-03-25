<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Collection;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class SizeValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that collection size is in the expected range and a valid result will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Collection\SizeValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Collection\SizeValidator::process()
     */
    public function testValid(): void
    {
        $validator = new SizeValidator(5, 15);
        $result = $validator->process([
            1,
            2,
            3,
            4,
            5,
            6,
            7,
        ]);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame([
            1,
            2,
            3,
            4,
            5,
            6,
            7,
        ], $result->getValue());
    }

    /**
     * Too few.
     *
     * Test that a collection has too few items and an invalid result will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Collection\SizeValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Collection\SizeValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Collection\SizeValidator::getTemplates()
     */
    public function testTooShort(): void
    {
        $validator = new SizeValidator(5);
        $result = $validator->process([
            1,
            2,
            3,
        ]);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Too many.
     *
     * Test that a collection has too many items and an invalid result will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Collection\SizeValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Collection\SizeValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Collection\SizeValidator::getTemplates()
     */
    public function testTooLong(): void
    {
        $validator = new SizeValidator(null, 5);
        $result = $validator->process([
            1,
            2,
            3,
            4,
            5,
            6,
            7,
        ]);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Not array.
     *
     * Test that none array value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Collection\SizeValidator::process()
     */
    public function testNotArray(): void
    {
        $validator = new SizeValidator();
        $result = $validator->process(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
