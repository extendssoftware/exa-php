<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Text;

use ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Validator\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class UrlValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that a valid URL will validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\UrlValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\UrlValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new UrlValidator();
        $result = $validator->validate('https://extends.nl/');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('https://extends.nl/', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that an invalid URL value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\UrlValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\UrlValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\UrlValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new UrlValidator();
        $result = $validator->validate('foo-bar-baz');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Scheme isn't allowed.
     *
     * Test that a not allowed scheme value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\UrlValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\UrlValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Text\UrlValidator::getTemplates()
     * @noinspection HttpUrlsUsage
     */
    public function testSchemeNotAllowed(): void
    {
        $validator = new UrlValidator(['https']);
        $result = $validator->validate('http://extends.nl');

        $this->assertInstanceOf(InvalidResult::class, $result);
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

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
