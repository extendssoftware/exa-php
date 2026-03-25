<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Collection;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class UniqueValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that the array contains unique values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Collection\UniqueValidator::process()
     */
    public function testValid(): void
    {
        $collection = new UniqueValidator();
        $result = $collection->process([1, 2, 3]);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame([1, 2, 3], $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that the array does not contain unique values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Collection\UniqueValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Collection\UniqueValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $collection = new UniqueValidator();
        $result = $collection->process([1, 1, 2]);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Not iterable.
     *
     * Test that the value is not iterable.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Collection\UniqueValidator::process()
     */
    public function testNotIterable(): void
    {
        $collection = new UniqueValidator();
        $result = $collection->process(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
