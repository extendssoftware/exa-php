<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Type;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class IterableValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that the array value is iterable.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\IterableValidator::process()
     */
    public function testValid(): void
    {
        $validator = new IterableValidator();
        $result = $validator->process([]);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame([], $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that a string value is not iterable.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\IterableValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\IterableValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new IterableValidator();
        $result = $validator->process('foo');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
