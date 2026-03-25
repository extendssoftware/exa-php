<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Logical;

use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class XorValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that only one of the inner validators is valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Logical\XorValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Logical\AbstractLogicalValidator::addProcessor()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Logical\AbstractLogicalValidator::getProcessors()
     */
    public function testValid(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->expects($this->exactly(4))
            ->method('isValid')
            ->willReturn(
                false,
                true,
                false,
                false,
            );

        $innerValidator = $this->createMock(ProcessorInterface::class);
        $innerValidator
            ->expects($this->exactly(4))
            ->method('process')
            ->with('foo', ['bar' => 'baz'])
            ->willReturn($result);

        $validator = new XorValidator();
        $result = $validator
            ->addProcessor($innerValidator)
            ->addProcessor($innerValidator)
            ->addProcessor($innerValidator)
            ->addProcessor($innerValidator)
            ->process('foo', ['bar' => 'baz']);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('foo', $result->getValue());
    }

    /**
     * None valid.
     *
     * Test that none of the inner validators are valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Logical\XorValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Logical\XorValidator::getTemplates()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Logical\AbstractLogicalValidator::addProcessor()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Logical\AbstractLogicalValidator::getProcessors()
     */
    public function testNoneValid(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->expects($this->exactly(4))
            ->method('isValid')
            ->willReturn(
                false,
                false,
                false,
                false,
            );

        $innerValidator = $this->createMock(ProcessorInterface::class);
        $innerValidator
            ->expects($this->exactly(4))
            ->method('process')
            ->with('foo', ['bar' => 'baz'])
            ->willReturn($result);

        $validator = new XorValidator();
        $result = $validator
            ->addProcessor($innerValidator)
            ->addProcessor($innerValidator)
            ->addProcessor($innerValidator)
            ->addProcessor($innerValidator)
            ->process('foo', ['bar' => 'baz']);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Multiple valid.
     *
     * Test that multiple of the inner validators are valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Logical\XorValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Logical\XorValidator::getTemplates()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Logical\AbstractLogicalValidator::addProcessor()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Logical\AbstractLogicalValidator::getProcessors()
     */
    public function testMultipleValid(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->expects($this->exactly(4))
            ->method('isValid')
            ->willReturn(
                false,
                true,
                true,
                false,
            );

        $innerValidator = $this->createMock(ProcessorInterface::class);
        $innerValidator
            ->expects($this->exactly(4))
            ->method('process')
            ->with('foo', ['bar' => 'baz'])
            ->willReturn($result);

        $validator = new XorValidator();
        $result = $validator
            ->addProcessor($innerValidator)
            ->addProcessor($innerValidator)
            ->addProcessor($innerValidator)
            ->addProcessor($innerValidator)
            ->process('foo', ['bar' => 'baz']);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
