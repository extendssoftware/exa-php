<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Object;

use PHPUnit\Framework\TestCase;

class PropertyValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that object property exists.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new PropertyValidator('foo');
        $result = $validator->validate((object)[
            'foo' => 'bar',
        ]);

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that object property not exists.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new PropertyValidator('foo');
        $result = $validator->validate((object)[
            'bar' => 'foo',
        ]);

        $this->assertFalse($result->isValid());
    }

    /**
     * Not object.
     *
     * Test that value is not an object.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\PropertyValidator::getTemplates()
     */
    public function testNotObject(): void
    {
        $validator = new PropertyValidator('foo');
        $result = $validator->validate([
            'bar' => 'foo',
        ]);

        $this->assertFalse($result->isValid());
    }
}
