<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use PHPUnit\Framework\TestCase;

class UuidValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that value 'db6eb6f2-1dda-4f06-a995-1fd1aca99e1f' is an valid UUID.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\UuidValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new UuidValidator();

        $this->assertTrue($validator->validate('db6eb6f2-1dda-1f06-a995-1fd1aca99e1f')->isValid()); // Version 1
        $this->assertTrue($validator->validate('db6eb6f2-1dda-2f06-a995-1fd1aca99e1f')->isValid()); // Version 2
        $this->assertTrue($validator->validate('db6eb6f2-1dda-3f06-a995-1fd1aca99e1f')->isValid()); // Version 3
        $this->assertTrue($validator->validate('db6eb6f2-1dda-4f06-a995-1fd1aca99e1f')->isValid()); // Version 4
        $this->assertTrue($validator->validate('db6eb6f2-1dda-5f06-a995-1fd1aca99e1f')->isValid()); // Version 5
    }

    /**
     * Invalid.
     *
     * Test that string value ''foo-bar-baz'' is a valid string.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\UuidValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\UuidValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new UuidValidator();

        $this->assertFalse($validator->validate('foo-bar-baz')->isValid());
        $this->assertFalse($validator->validate('db6eb6f2-1dda-6f06-a995-1fd1aca99e1f')->isValid()); // Version unknown
    }

    /**
     * Invalid.
     *
     * Test that none string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\UuidValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new UuidValidator();
        $result = $validator->validate(9);

        $this->assertFalse($result->isValid());
    }
}
