<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Type;

use PHPUnit\Framework\TestCase;

class IterableValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that array value is iterable.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\IterableValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new IterableValidator();
        $result = $validator->validate([]);

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that string value is not iterable.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\IterableValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\IterableValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new IterableValidator();
        $result = $validator->validate('foo');

        $this->assertFalse($result->isValid());
    }
}
