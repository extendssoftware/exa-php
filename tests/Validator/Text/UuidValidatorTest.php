<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
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

        $result1 = $validator->validate('db6eb6f2-1dda-1f06-a995-1fd1aca99e1f'); // Version 1
        $result2 = $validator->validate('db6eb6f2-1dda-2f06-a995-1fd1aca99e1f'); // Version 2
        $result3 = $validator->validate('db6eb6f2-1dda-3f06-a995-1fd1aca99e1f'); // Version 3
        $result4 = $validator->validate('db6eb6f2-1dda-4f06-a995-1fd1aca99e1f'); // Version 4
        $result5 = $validator->validate('db6eb6f2-1dda-5f06-a995-1fd1aca99e1f'); // Version 5

        $this->assertInstanceOf(ValidResult::class, $result1);
        $this->assertSame('db6eb6f2-1dda-1f06-a995-1fd1aca99e1f', $result1->getValue());

        $this->assertInstanceOf(ValidResult::class, $result2);
        $this->assertSame('db6eb6f2-1dda-2f06-a995-1fd1aca99e1f', $result2->getValue());

        $this->assertInstanceOf(ValidResult::class, $result3);
        $this->assertSame('db6eb6f2-1dda-3f06-a995-1fd1aca99e1f', $result3->getValue());

        $this->assertInstanceOf(ValidResult::class, $result4);
        $this->assertSame('db6eb6f2-1dda-4f06-a995-1fd1aca99e1f', $result4->getValue());

        $this->assertInstanceOf(ValidResult::class, $result5);
        $this->assertSame('db6eb6f2-1dda-5f06-a995-1fd1aca99e1f', $result5->getValue());
    }

    /**
     * Invalid.
     *
     * Test that the string value '' foo-bar-baz '' is a valid string.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\UuidValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\UuidValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new UuidValidator();
        $result1 = $validator->validate('foo-bar-baz');
        $result2 = $validator->validate('db6eb6f2-1dda-6f06-a995-1fd1aca99e1f'); // Version unknown

        $this->assertInstanceOf(InvalidResult::class, $result1);
        $this->assertInstanceOf(InvalidResult::class, $result2);
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

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
