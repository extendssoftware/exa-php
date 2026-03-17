<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Type;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class IterableValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that the array value is iterable.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\IterableValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new IterableValidator();
        $result = $validator->validate([]);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame([], $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that a string value is not iterable.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\IterableValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\IterableValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new IterableValidator();
        $result = $validator->validate('foo');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
