<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Collection;

use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class ContainsValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that collection will be valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\ContainsValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\ContainsValidator::validate()
     */
    public function testValid(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->method('isValid')
            ->willReturn(true);

        $validator = $this->createMock(ValidatorInterface::class);
        $validator
            ->expects($this->exactly(3))
            ->method('validate')
            ->with('foo', 'bar')
            ->willReturn($result);

        /**
         * @var ValidatorInterface $validator
         */
        $collection = new ContainsValidator($validator);
        $result = $collection->validate([
            'foo',
            'foo',
            'foo',
        ], 'bar');

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that collection will be invalid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\ContainsValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\ContainsValidator::validate()
     */
    public function testInvalid(): void
    {
        $result = $this->createMock(ResultInterface::class);
        $result
            ->method('isValid')
            ->willReturn(false);

        $validator = $this->createMock(ValidatorInterface::class);
        $validator
            ->expects($this->exactly(3))
            ->method('validate')
            ->with('foo', 'bar')
            ->willReturn($result);

        /**
         * @var ValidatorInterface $validator
         */
        $collection = new ContainsValidator($validator);
        $result = $collection->validate([
            'foo',
            'foo',
            'foo',
        ], 'bar');

        $this->assertFalse($result->isValid());
    }

    /**
     * Not iterable.
     *
     * Test that collection will be invalid when value is not iterable.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\ContainsValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\ContainsValidator::validate()
     */
    public function testNotIterable(): void
    {
        $validator = $this->createMock(ValidatorInterface::class);

        /**
         * @var ValidatorInterface $validator
         */
        $collection = new ContainsValidator($validator);
        $result = $collection->validate(9);

        $this->assertFalse($result->isValid());
    }
}
