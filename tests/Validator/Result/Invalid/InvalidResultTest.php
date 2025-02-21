<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Result\Invalid;

use PHPUnit\Framework\TestCase;

class InvalidResultTest extends TestCase
{
    /**
     * Methods.
     *
     * Test that methods return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult::getCode()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult::getMessage()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult::getParameters()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult::isValid()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult::isValid()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult::jsonSerialize()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Result\Invalid\InvalidResult::__toString()
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
}
