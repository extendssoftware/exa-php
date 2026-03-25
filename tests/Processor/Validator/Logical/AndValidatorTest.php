<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Logical;

use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class AndValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that all the inner validators will be validated.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Logical\AndValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Logical\AndValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Logical\AbstractLogicalValidator::addProcessor()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Logical\AbstractLogicalValidator::getProcessors()
     */
    public function testValid(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->expects($this->exactly(3))
            ->method('isValid')
            ->willReturn(true);

        $innerValidator = $this->createMock(ProcessorInterface::class);
        $innerValidator
            ->expects($this->exactly(3))
            ->method('process')
            ->with('foo', ['bar' => 'baz'])
            ->willReturn($result);

        $validator = new AndValidator([
            $innerValidator,
            $innerValidator,
            $innerValidator,
        ]);
        $result = $validator->process('foo', ['bar' => 'baz']);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('foo', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that all the inner validators will be validated.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Logical\AndValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Logical\AndValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Logical\AbstractLogicalValidator::addProcessor()
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

        $inner = $this->createMock(ProcessorInterface::class);
        $inner
            ->expects($this->exactly(2))
            ->method('process')
            ->with('foo', ['bar' => 'baz'])
            ->willReturn($expectedResult);

        $validator = (new AndValidator())
            ->addProcessor($inner)
            ->addProcessor($inner)
            ->addProcessor($inner);
        $actualResult = $validator->process('foo', ['bar' => 'baz']);

        $this->assertSame($expectedResult, $actualResult);
    }
}
