<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Type;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
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
        $validator = new ObjectValidator();
        $result = $validator->validate(new stdClass());

        $this->assertTrue($result->isValid());
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

        $this->assertFalse($result->isValid());
    }
}
