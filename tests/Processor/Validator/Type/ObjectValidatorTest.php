<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Type;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;
use stdClass;

class ObjectValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that stdClass is a valid object.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\ObjectValidator::process()
     */
    public function testValid(): void
    {
        $class = new stdClass();

        $validator = new ObjectValidator();
        $result = $validator->process($class);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame($class, $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that string value 'foo' is not an object.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\ObjectValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Type\ObjectValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new ObjectValidator();
        $result = $validator->process('foo');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
