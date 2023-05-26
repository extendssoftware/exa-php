<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Object;

use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class OptionalPropertyValidatorTest extends TestCase
{
    /**
     * Validate.
     *
     * Test that validator will act as a proxy to the inner validator.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\OptionalPropertyValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\OptionalPropertyValidator::validate()
     */
    public function testValidate(): void
    {
        $result = $this->createMock(ResultInterface::class);

        $validator = $this->createMock(ValidatorInterface::class);
        $validator
            ->expects($this->once())
            ->method('validate')
            ->with('foo', ['bar'])
            ->willReturn($result);

        /**
         * @var ValidatorInterface $validator
         */
        $optionalValidator = new OptionalPropertyValidator($validator);

        $this->assertSame($result, $optionalValidator->validate('foo', ['bar']));
    }
}
