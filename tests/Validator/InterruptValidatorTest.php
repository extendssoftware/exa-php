<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator;

use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use PHPUnit\Framework\TestCase;

class InterruptValidatorTest extends TestCase
{
    /**
     * Validate.
     *
     * Test that get method will return correct values and validate calls inner validator validate method.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\InterruptValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\InterruptValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\InterruptValidator::mustInterrupt()
     */
    public function testValidate(): void
    {
        $innerResult = $this->createMock(ResultInterface::class);

        $innerValidator = $this->createMock(ValidatorInterface::class);
        $innerValidator
            ->expects($this->once())
            ->method('validate')
            ->with('foo', 'bar')
            ->willReturn($innerResult);

        $validator = new InterruptValidator($innerValidator, true);
        $result = $validator->validate('foo', 'bar');

        $this->assertTrue($validator->mustInterrupt());
        $this->assertSame($result, $result);
    }
}
