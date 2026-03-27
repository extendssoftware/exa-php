<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Parser\Type;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

#[CoversClass(NumberParser::class)]
class NumberParserTest extends TestCase
{
    public static function validNumberProvider(): array
    {
        return [
            'integer string' => ['10', 10.0],
            'float string' => ['10.5', 10.5],
            'float with trailing zeros' => ['10.50', 10.5],
            'negative integer' => ['-10', -10.0],
            'negative float' => ['-10.5', -10.5],
            'zero string' => ['0', 0.0],
            'zero float string' => ['0.0', 0.0],
            'integer int' => [10, 10.0],
            'float value' => [10.5, 10.5],
            'numeric with spaces' => [' 10.5 ', 10.5],
            'leading zeros' => ['010', 10.0],
            'scientific notation lower' => ['1e3', 1000.0],
            'scientific notation upper' => ['1E3', 1000.0],
            'decimal scientific' => ['1.5e2', 150.0],
            'plus sign' => ['+10', 10.0],
        ];
    }

    public static function invalidNumberProvider(): array
    {
        return [
            'empty string' => [''],
            'whitespace only' => ['   '],
            'alpha string' => ['abc'],
            'alphanumeric' => ['10abc'],
            'comma decimal (EU format)' => ['10,5'],
            'multiple dots' => ['10.5.2'],
            'just dash' => ['-'],
            'just plus' => ['+'],
            'null value' => [null],
            'boolean true' => [true],
            'boolean false' => [false],
            'array' => [[10]],
            'object' => [new stdClass()],
            'hex string' => ['0x1A'],
        ];
    }

    #[Test]
    #[DataProvider('validNumberProvider')]
    public function testValid(mixed $value, float $expected): void
    {
        $validator = new NumberParser();
        $result = $validator->process($value);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame($expected, $result->getValue());
    }

    #[Test]
    #[DataProvider('invalidNumberProvider')]
    public function testInvalid(mixed $value): void
    {
        $validator = new NumberParser();
        $result = $validator->process($value);

        $this->assertInstanceOf(InvalidResult::class, $result);
        $this->assertSame(NumberParser::NOT_NUMERIC, $result->getCode());
    }
}
