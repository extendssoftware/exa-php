<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Logical;

use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class AndValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that all the inner validators will be validated.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Logical\AndValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Logical\AndValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Logical\AbstractLogicalValidator::addValidator()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Logical\AbstractLogicalValidator::getValidators()
     */
    public function testValid(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->expects($this->exactly(3))
            ->method('isValid')
            ->willReturn(true);

        $innerValidator = $this->createMock(ValidatorInterface::class);
        $innerValidator
            ->expects($this->exactly(3))
            ->method('validate')
            ->with('foo', ['bar' => 'baz'])
            ->willReturn($result);

        $validator = new AndValidator([
            $innerValidator,
            $innerValidator,
            $innerValidator,
        ]);
        $result = $validator->validate('foo', ['bar' => 'baz']);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('foo', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that all the inner validators will be validated.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Logical\AndValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Logical\AndValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Logical\AbstractLogicalValidator::addValidator()
     */
    public function testInvalid(): void
    {
        $expectedResult = $this->createMock(ResultInterface::class);
        $expectedResult
            ->expects($this->exactly(2))
            ->method('isValid')
            ->willReturnOnConsecutiveCalls(
                true,
                false,
            );

        $inner = $this->createMock(ValidatorInterface::class);
        $inner
            ->expects($this->exactly(2))
            ->method('validate')
            ->with('foo', ['bar' => 'baz'])
            ->willReturn($expectedResult);

        $validator = (new AndValidator())
            ->addValidator($inner)
            ->addValidator($inner)
            ->addValidator($inner);
        $actualResult = $validator->validate('foo', ['bar' => 'baz']);

        $this->assertSame($expectedResult, $actualResult);
    }
}
