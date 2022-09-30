<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Other;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class NullableValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that value will be passed to inner validator and result will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Other\NullableValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Other\NullableValidator::validate()
     */
    public function testValid(): void
    {
        $innerResult = $this->createMock(ResultInterface::class);

        $innerValidator = $this->createMock(ValidatorInterface::class);
        $innerValidator
            ->expects($this->once())
            ->method('validate')
            ->with('foo')
            ->willReturn($innerResult);

        /**
         * @var ValidatorInterface $innerValidator
         */
        $validator = new NullableValidator($innerValidator);

        $result = $validator->validate('foo');
        $this->assertSame($innerResult, $result);
    }

    /**
     * Valid null value.
     *
     * Test that NULL is a valid value.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Other\NullableValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Other\NullableValidator::validate()
     */
    public function testValidNullValue(): void
    {
        $inner = $this->createMock(ValidatorInterface::class);
        $inner
            ->expects($this->never())
            ->method('validate');

        /**
         * @var ValidatorInterface $inner
         */
        $validator = new NullableValidator($inner);

        $result = $validator->validate(null);
        $this->assertTrue($result->isValid());
    }

    /**
     * Factory.
     *
     * Test that factory returns an instanceof of ValidatorInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Other\NullableValidator::factory()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Other\NullableValidator::__construct()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('getService')
            ->with(ValidatorInterface::class, ['foo' => 'bar'])
            ->willReturn($this->createMock(ValidatorInterface::class));

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = NullableValidator::factory(NullableValidator::class, $serviceLocator, [
            'name' => ValidatorInterface::class,
            'options' => [
                'foo' => 'bar',
            ],
        ]);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
