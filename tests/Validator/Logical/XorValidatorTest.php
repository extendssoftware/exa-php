<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Logical;

use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class XorValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that only one of the inner validators is valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Logical\XorValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Logical\AbstractLogicalValidator::addValidator()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Logical\AbstractLogicalValidator::getValidators()
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
                false
            );

        $innerValidator = $this->createMock(ValidatorInterface::class);
        $innerValidator
            ->expects($this->exactly(4))
            ->method('validate')
            ->with('foo', ['bar' => 'baz'])
            ->willReturn($result);

        /**
         * @var ValidatorInterface $innerValidator
         */
        $validator = new XorValidator();
        $result = $validator
            ->addValidator($innerValidator)
            ->addValidator($innerValidator)
            ->addValidator($innerValidator)
            ->addValidator($innerValidator)
            ->validate('foo', ['bar' => 'baz']);

        $this->assertTrue($result->isValid());
    }

    /**
     * None valid.
     *
     * Test that none of the inner validators are valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Logical\XorValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Logical\XorValidator::getTemplates()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Logical\AbstractLogicalValidator::addValidator()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Logical\AbstractLogicalValidator::getValidators()
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
                false
            );

        $innerValidator = $this->createMock(ValidatorInterface::class);
        $innerValidator
            ->expects($this->exactly(4))
            ->method('validate')
            ->with('foo', ['bar' => 'baz'])
            ->willReturn($result);

        /**
         * @var ValidatorInterface $innerValidator
         */
        $validator = new XorValidator();
        $result = $validator
            ->addValidator($innerValidator)
            ->addValidator($innerValidator)
            ->addValidator($innerValidator)
            ->addValidator($innerValidator)
            ->validate('foo', ['bar' => 'baz']);

        $this->assertFalse($result->isValid());
    }

    /**
     * Multiple valid.
     *
     * Test that multiple of the inner validators are valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Logical\XorValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Logical\XorValidator::getTemplates()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Logical\AbstractLogicalValidator::addValidator()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Logical\AbstractLogicalValidator::getValidators()
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
                false
            );

        $innerValidator = $this->createMock(ValidatorInterface::class);
        $innerValidator
            ->expects($this->exactly(4))
            ->method('validate')
            ->with('foo', ['bar' => 'baz'])
            ->willReturn($result);

        /**
         * @var ValidatorInterface $innerValidator
         */
        $validator = new XorValidator();
        $result = $validator
            ->addValidator($innerValidator)
            ->addValidator($innerValidator)
            ->addValidator($innerValidator)
            ->addValidator($innerValidator)
            ->validate('foo', ['bar' => 'baz']);

        $this->assertFalse($result->isValid());
    }
}
