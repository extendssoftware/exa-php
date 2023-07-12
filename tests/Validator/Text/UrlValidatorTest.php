<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use PHPUnit\Framework\TestCase;

class UrlValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that an valid URL will validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\UrlValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new UrlValidator();
        $result = $validator->validate('https://extends.nl/');

        $this->assertTrue($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that an invalid URL value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\UrlValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\UrlValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new UrlValidator();
        $result = $validator->validate('foo-bar-baz');

        $this->assertFalse($result->isValid());
    }

    /**
     * Invalid.
     *
     * Test that none string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\UrlValidator::validate()
     */
    public function testNotString(): void
    {
        $validator = new UrlValidator();
        $result = $validator->validate(9);

        $this->assertFalse($result->isValid());
    }
}
