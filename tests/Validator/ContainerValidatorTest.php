<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator;

use ExtendsSoftware\ExaPHP\Validator\Result\Container\Array\ArrayContainerResult;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use PHPUnit\Framework\TestCase;

class ContainerValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that sub validators will be validated and a valid result container will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\ContainerValidator::addValidator()
     * @covers \ExtendsSoftware\ExaPHP\Validator\ContainerValidator::validate()
     */
    public function testValid(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->expects($this->exactly(8))
            ->method('isValid')
            ->willReturn(true);

        $validator = $this->createMock(ValidatorInterface::class);
        $validator
            ->expects($this->exactly(4))
            ->method('validate')
            ->with('foo', 'bar')
            ->willReturn($result);

        $container = new ContainerValidator();
        $result = $container
            ->addValidator($validator)
            ->addValidator($validator)
            ->addValidator($validator)
            ->addValidator($validator)
            ->validate('foo', 'bar');

        $this->assertInstanceOf(ArrayContainerResult::class, $result);
        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that sub validators will be validated and an invalid result container will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\ContainerValidator::addValidator()
     * @covers \ExtendsSoftware\ExaPHP\Validator\ContainerValidator::validate()
     */
    public function testInvalid(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->expects($this->exactly(5))
            ->method('isValid')
            ->willReturnOnConsecutiveCalls(
                true,
                true,
                false,
                false,
                false,
            );

        $validator = $this->createMock(ValidatorInterface::class);
        $validator
            ->expects($this->exactly(3))
            ->method('validate')
            ->with('foo', 'bar')
            ->willReturn($result);

        $container = new ContainerValidator();
        $result = $container
            ->addValidator($validator)
            ->addValidator($validator)
            ->addValidator($validator, true)
            ->addValidator($validator)
            ->validate('foo', 'bar');

        $this->assertInstanceOf(ArrayContainerResult::class, $result);
        $this->assertFalse($result->isValid());
    }
}
