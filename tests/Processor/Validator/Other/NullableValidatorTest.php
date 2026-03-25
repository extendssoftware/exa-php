<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Other;

use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class NullableValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that value will be passed to inner validator and result will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Other\NullableValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Other\NullableValidator::process()
     */
    public function testValid(): void
    {
        $innerResult = $this->createMock(ResultInterface::class);

        $innerValidator = $this->createMock(ProcessorInterface::class);
        $innerValidator
            ->expects($this->once())
            ->method('process')
            ->with('foo')
            ->willReturn($innerResult);

        $validator = new NullableValidator($innerValidator);
        $result = $validator->process('foo');

        $this->assertSame($innerResult, $result);
    }

    /**
     * Valid null value.
     *
     * Test that NULL is a valid value.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Other\NullableValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Other\NullableValidator::process()
     */
    public function testValidNullValue(): void
    {
        $inner = $this->createMock(ProcessorInterface::class);
        $inner
            ->expects($this->never())
            ->method('process');

        $validator = new NullableValidator($inner);
        $result = $validator->process(null);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame(null, $result->getValue());
    }
}
