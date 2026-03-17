<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator;

use ExtendsSoftware\ExaPHP\Transformer\TransformerInterface;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use PHPUnit\Framework\TestCase;

class TransformingValidatorTest extends TestCase
{
    /**
     * Assert that the transformer will transform the value before calling the validator.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\TransformingValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\TransformingValidator::validate()
     */
    public function testItTransformsValueBeforeValidation(): void
    {
        $transformer = $this->createMock(TransformerInterface::class);
        $transformer
            ->expects($this->once())
            ->method('transform')
            ->with('foo')
            ->willReturn('bar');

        $expectedResult = $this->createMock(ResultInterface::class);

        $wrappedValidator = $this->createMock(ValidatorInterface::class);
        $wrappedValidator
            ->expects($this->once())
            ->method('validate')
            ->with('bar', ['foo' => 'bar'])
            ->willReturn($expectedResult);

        $transformingValidator = new TransformingValidator($transformer, $wrappedValidator);
        $actualResult = $transformingValidator->validate('foo', ['foo' => 'bar']);

        $this->assertSame($expectedResult, $actualResult);
    }
}
