<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class NotEmptyValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that value is not an empty string.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NotEmptyValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new NotEmptyValidator();

        $this->assertTrue($validator->validate('foo')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that value is an empty string.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NotEmptyValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NotEmptyValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new NotEmptyValidator();

        $this->assertFalse($validator->validate('')->isValid());
    }

    /**
     * Invalid.
     *
     * Test that none string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\NotEmptyValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new NotEmptyValidator();
        $result = $validator->validate(9);

        $this->assertFalse($result->isValid());
    }
}
