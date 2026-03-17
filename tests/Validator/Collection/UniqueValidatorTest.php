<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Collection;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class UniqueValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that the array contains unique values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\UniqueValidator::validate()
     */
    public function testValid(): void
    {
        $collection = new UniqueValidator();
        $result = $collection->validate([1, 2, 3]);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame([1, 2, 3], $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that the array does not contain unique values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\UniqueValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\UniqueValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $collection = new UniqueValidator();
        $result = $collection->validate([1, 1, 2]);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Not iterable.
     *
     * Test that the value is not iterable.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\UniqueValidator::validate()
     */
    public function testNotIterable(): void
    {
        $collection = new UniqueValidator();
        $result = $collection->validate(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
