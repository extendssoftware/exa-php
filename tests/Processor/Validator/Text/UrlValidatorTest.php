<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Text;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class UrlValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that a valid URL will validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\UrlValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\UrlValidator::process()
     */
    public function testValid(): void
    {
        $validator = new UrlValidator();
        $result = $validator->process('https://extends.nl/');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('https://extends.nl/', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that an invalid URL value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\UrlValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\UrlValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\UrlValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new UrlValidator();
        $result = $validator->process('foo-bar-baz');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Scheme isn't allowed.
     *
     * Test that a not allowed scheme value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\UrlValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\UrlValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\UrlValidator::getTemplates()
     * @noinspection HttpUrlsUsage
     */
    public function testSchemeNotAllowed(): void
    {
        $validator = new UrlValidator(['https']);
        $result = $validator->process('http://extends.nl');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Invalid.
     *
     * Test that none string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\UrlValidator::process()
     */
    public function testNotString(): void
    {
        $validator = new UrlValidator();
        $result = $validator->process(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
