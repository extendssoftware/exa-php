<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Other;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class RangeValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that the left value is not greater than the right value and a valid result will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Other\RangeValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Other\RangeValidator::validate()
     */
    public function testValid(): void
    {
        $leftResult = $this->createMock(ResultInterface::class);
        $leftResult
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $leftValidator = $this->createMock(ValidatorInterface::class);
        $leftValidator
            ->expects($this->once())
            ->method('validate')
            ->with(10)
            ->willReturn($leftResult);

        $rightResult = $this->createMock(ResultInterface::class);
        $rightResult
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $rightValidator = $this->createMock(ValidatorInterface::class);
        $rightValidator
            ->expects($this->once())
            ->method('validate')
            ->with(20)
            ->willReturn($rightResult);

        $validator = new RangeValidator($leftValidator, $rightValidator, 'min', 'max');
        $result = $validator->validate(
            (object)[
                'min' => 10,
                'max' => 20,
            ],
        );

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertEquals(
            (object)[
                'min' => 10,
                'max' => 20,
            ],
            $result->getValue(),
        );
    }

    /**
     * Invalid value.
     *
     * Test that a non-object value will return an invalid result.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Other\RangeValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Other\RangeValidator::validate()
     */
    public function testInvalidValue(): void
    {
        $leftValidator = $this->createMock(ValidatorInterface::class);

        $rightValidator = $this->createMock(ValidatorInterface::class);

        $validator = new RangeValidator($leftValidator, $rightValidator, 'min', 'max');
        $result = $validator->validate(
            [
                'min' => 10,
                'max' => 20,
            ],
        );

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Invalid range inclusive.
     *
     * Test that the left value is greater than the right value or the same, and an invalid result will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Other\RangeValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Other\RangeValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Other\RangeValidator::getTemplates()
     */
    public function testInvalidRangeInclusive(): void
    {
        $leftResult = $this->createMock(ResultInterface::class);
        $leftResult
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $leftValidator = $this->createMock(ValidatorInterface::class);
        $leftValidator
            ->expects($this->once())
            ->method('validate')
            ->with(20)
            ->willReturn($leftResult);

        $rightResult = $this->createMock(ResultInterface::class);
        $rightResult
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $rightValidator = $this->createMock(ValidatorInterface::class);
        $rightValidator
            ->expects($this->once())
            ->method('validate')
            ->with(10)
            ->willReturn($rightResult);

        $validator = new RangeValidator($leftValidator, $rightValidator, 'min', 'max', true);
        $result = $validator->validate(
            (object)[
                'min' => 20,
                'max' => 10,
            ],
        );

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Invalid range non-inclusive.
     *
     * Test that the left value is greater than the right value and an invalid result will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Other\RangeValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Other\RangeValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Other\RangeValidator::getTemplates()
     */
    public function testInvalidRangeNonInclusive(): void
    {
        $leftResult = $this->createMock(ResultInterface::class);
        $leftResult
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $leftValidator = $this->createMock(ValidatorInterface::class);
        $leftValidator
            ->expects($this->once())
            ->method('validate')
            ->with(10)
            ->willReturn($leftResult);

        $rightResult = $this->createMock(ResultInterface::class);
        $rightResult
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $rightValidator = $this->createMock(ValidatorInterface::class);
        $rightValidator
            ->expects($this->once())
            ->method('validate')
            ->with(10)
            ->willReturn($rightResult);

        $validator = new RangeValidator($leftValidator, $rightValidator, inclusive: false);
        $result = $validator->validate(
            (object)[
                'left' => 10,
                'right' => 10,
            ],
        );

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
