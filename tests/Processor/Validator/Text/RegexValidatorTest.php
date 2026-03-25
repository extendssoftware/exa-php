<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Text;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class RegexValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that valid uuid value will validate against regular expression.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\RegexValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\RegexValidator::process()
     */
    public function testValid(): void
    {
        $validator = new RegexValidator('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i');
        $result = $validator->process('db6eb6f2-1dda-4f06-a995-1fd1aca99e1f');

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame('db6eb6f2-1dda-4f06-a995-1fd1aca99e1f', $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that invalid uuid value will not validate against regular expression.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\RegexValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\RegexValidator::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\RegexValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new RegexValidator('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i');
        $result = $validator->process('foo-bar-baz');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Invalid.
     *
     * Test that none string value will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\RegexValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Validator\Text\RegexValidator::process()
     */
    public function testNotString(): void
    {
        $validator = new RegexValidator('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i');
        $result = $validator->process(9);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
