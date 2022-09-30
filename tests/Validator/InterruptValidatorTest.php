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
        $result = $this->createMock(ResultInterface::class);

        $validator = $this->createMock(ValidatorInterface::class);
        $validator
            ->expects($this->once())
            ->method('validate')
            ->with('foo', 'bar')
            ->willReturn($result);

        /**
         * @var ValidatorInterface $validator
         */
        $interrupt = new InterruptValidator($validator, true);

        $this->assertSame($result, $interrupt->validate('foo', 'bar'));
        $this->assertTrue($interrupt->mustInterrupt());
    }
}
