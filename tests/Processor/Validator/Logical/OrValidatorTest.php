<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Logical;

use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class OrValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that one of the inner validators are valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Logical\OrValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Logical\AbstractLogicalValidator::addProcessor()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Logical\AbstractLogicalValidator::getProcessors()
     */
    public function testValid(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->expects($this->exactly(2))
            ->method('isValid')
            ->willReturnOnConsecutiveCalls(
                false,
                true,
            );

        $innerValidator = $this->createMock(ProcessorInterface::class);
        $innerValidator
            ->expects($this->exactly(2))
            ->method('process')
            ->with('foo', ['bar' => 'baz'])
            ->willReturn($result);

        $validator = new OrValidator();
        $result = $validator
            ->addProcessor($innerValidator)
            ->addProcessor($innerValidator)
            ->addProcessor($innerValidator)
            ->process('foo', ['bar' => 'baz']);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('foo', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that one of the inner validators are valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Logical\OrValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Logical\OrValidator::getTemplates()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Logical\AbstractLogicalValidator::addProcessor()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Logical\AbstractLogicalValidator::getProcessors()
     */
    public function testInvalid(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->expects($this->exactly(2))
            ->method('isValid')
            ->willReturnOnConsecutiveCalls(
                false,
                false,
            );

        $innerValidator = $this->createMock(ProcessorInterface::class);
        $innerValidator
            ->expects($this->exactly(2))
            ->method('process')
            ->with('foo', ['bar' => 'baz'])
            ->willReturn($result);

        $validator = new OrValidator();
        $result = $validator
            ->addProcessor($innerValidator)
            ->addProcessor($innerValidator)
            ->process('foo', ['bar' => 'baz']);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
