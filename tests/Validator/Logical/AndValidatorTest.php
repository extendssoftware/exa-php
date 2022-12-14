<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Logical;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class AndValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that all the inner validators will be validated.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Logical\AndValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Logical\AbstractLogicalValidator::addValidator()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Logical\AbstractLogicalValidator::getValidators()
     */
    public function testValid(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->expects($this->exactly(3))
            ->method('isValid')
            ->willReturn(true);

        $innerValidator = $this->createMock(ValidatorInterface::class);
        $innerValidator
            ->expects($this->exactly(3))
            ->method('validate')
            ->with('foo', ['bar' => 'baz'])
            ->willReturn($result);

        /**
         * @var ValidatorInterface $innerValidator
         */
        $validator = new AndValidator();
        $result = $validator
            ->addValidator($innerValidator)
            ->addValidator($innerValidator)
            ->addValidator($innerValidator)
            ->validate('foo', ['bar' => 'baz']);

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that all the inner validators will be validated.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Logical\AndValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Logical\AbstractLogicalValidator::addValidator()
     */
    public function testInvalid(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->expects($this->exactly(2))
            ->method('isValid')
            ->willReturnOnConsecutiveCalls(
                true,
                false
            );

        $inner = $this->createMock(ValidatorInterface::class);
        $inner
            ->expects($this->exactly(2))
            ->method('validate')
            ->with('foo', ['bar' => 'baz'])
            ->willReturn($result);

        /**
         * @var ValidatorInterface $inner
         */
        $this->assertSame(
            $result,
            (new AndValidator())
                ->addValidator($inner)
                ->addValidator($inner)
                ->addValidator($inner)
                ->validate('foo', ['bar' => 'baz'])
        );
    }

    /**
     * Factory.
     *
     * Test that factory returns a AndValidator.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Logical\AndValidator::factory()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Logical\AndValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Logical\AndValidator::addValidator()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('getService')
            ->with(
                ValidatorInterface::class,
                [
                    'foo' => 'bar',
                ]
            )
            ->willReturn($this->createMock(ValidatorInterface::class));

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = AndValidator::factory(ValidatorInterface::class, $serviceLocator, [
            'validators' => [
                [
                    'name' => ValidatorInterface::class,
                    'options' => [
                        'foo' => 'bar',
                    ],
                ],
            ],
        ]);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
