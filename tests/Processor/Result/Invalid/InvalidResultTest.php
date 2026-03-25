<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Result\Invalid;

use ExtendsSoftware\ExaPHP\Processor\Result\Exception\ResultNotValid;
use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use PHPUnit\Framework\TestCase;

class InvalidResultTest extends TestCase
{
    /**
     * Methods.
     *
     * Test that methods return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult::getCode()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult::getMessage()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult::getParameters()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult::isValid()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult::isValid()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult::jsonSerialize()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult::__toString()
     */
    public function testMethods(): void
    {
        $result = new InvalidResult('notString', 'Value is not a string, got "{{type}}".', [
            'type' => 'array',
        ]);

        $this->assertSame('notString', $result->getCode());
        $this->assertSame('Value is not a string, got "{{type}}".', $result->getMessage());
        $this->assertSame([
            'type' => 'array',
        ], $result->getParameters());
        $this->assertFalse($result->isValid());
        $this->assertEquals([
            'code' => 'notString',
            'message' => 'Value is not a string, got "{{type}}".',
            'parameters' => (object)[
                'type' => 'array',
            ],
        ], $result->jsonSerialize());
        $this->assertSame('Value is not a string, got "array".', (string)$result);
    }

    /**
     * Get value.
     *
     * Test that method throws an exception.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult::getValue()
     */
    public function testGetValue(): void
    {
        $this->expectException(ResultNotValid::class);
        $this->expectExceptionMessage('Can not get value from an invalid result.');

        $result = new InvalidResult('notString', 'Value is not a string, got "{{type}}".', [
            'type' => 'array',
        ]);
        $result->getValue();
    }
}
