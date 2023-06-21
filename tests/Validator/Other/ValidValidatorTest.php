<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Other;

use PHPUnit\Framework\TestCase;

class ValidValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that any value is valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Other\ValidValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new ValidValidator();

        $this->assertTrue($validator->validate('foo')->isValid());
        $this->assertTrue($validator->validate(9)->isValid());
        $this->assertTrue($validator->validate(9.5)->isValid());
        $this->assertTrue($validator->validate(['foo'])->isValid());
        $this->assertTrue($validator->validate(false)->isValid());
    }
}
