<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Collection;

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

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that the array not contains unique values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\UniqueValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\UniqueValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $collection = new UniqueValidator();
        $result = $collection->validate([1, 1, 2]);

        $this->assertFalse($result->isValid());
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

        $this->assertFalse($result->isValid());
    }
}
