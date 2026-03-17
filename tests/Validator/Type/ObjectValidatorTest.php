<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Type;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;
use stdClass;

class ObjectValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that stdClass is a valid object.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\ObjectValidator::validate()
     */
    public function testValid(): void
    {
        $class = new stdClass();

        $validator = new ObjectValidator();
        $result = $validator->validate($class);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame($class, $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that string value 'foo' is not an object.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\ObjectValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Type\ObjectValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new ObjectValidator();
        $result = $validator->validate('foo');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
