<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Validator\Other;

use ExtendsSoftware\ExaPHP\Processor\Exception\TemplateNotFound;
use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use ExtendsSoftware\ExaPHP\Processor\Validator\Text\DateTimeValidator;
use PHPUnit\Framework\TestCase;

class BetweenValidatorTest extends TestCase
{
    /**
     * Valid inclusive values.
     *
     * @return array<array<float>>
     */
    public static function validInclusiveDataProvider(): array
    {
        return [
            [1],
            [5],
            [10],
        ];
    }

    /**
     * Invalid inclusive values.
     *
     * @return array<array<float>>
     */
    public static function invalidInclusiveDataProvider(): array
    {
        return [
            [0],
            [11],
        ];
    }

    /**
     * Invalid exclusive values.
     *
     * @return array<array<float>>
     */
    public static function invalidExclusiveDataProvider(): array
    {
        return [
            [1],
            [10],
        ];
    }

    /**
     * Valid.
     *
     * Test that values are valid.
     *
     * @param int $integer
     *
     * @covers       \ExtendsSoftware\ExaPHP\Processor\Validator\Other\BetweenValidator::__construct()
     * @covers       \ExtendsSoftware\ExaPHP\Processor\Validator\Other\BetweenValidator::process()
     * @covers       \ExtendsSoftware\ExaPHP\Processor\Validator\Other\BetweenValidator::getTemplates()
     * @dataProvider validInclusiveDataProvider
     */
    public function testValid(int $integer): void
    {
        $validator = new BetweenValidator(1, 10);
        $result = $validator->process($integer);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame($integer, $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that values are invalid.
     *
     * @param int $integer
     *
     * @throws TemplateNotFound
     * @covers       \ExtendsSoftware\ExaPHP\Processor\Validator\Other\BetweenValidator::__construct()
     * @covers       \ExtendsSoftware\ExaPHP\Processor\Validator\Other\BetweenValidator::process()
     * @covers       \ExtendsSoftware\ExaPHP\Processor\Validator\Other\BetweenValidator::getTemplates()
     * @dataProvider invalidInclusiveDataProvider
     */
    public function testInvalid(int $integer): void
    {
        $validator = new BetweenValidator(1, 10);
        $result = $validator->process($integer);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Exclusive.
     *
     * Test that bound values are invalid when inclusive is false.
     *
     * @param int $integer
     *
     * @covers       \ExtendsSoftware\ExaPHP\Processor\Validator\Other\BetweenValidator::__construct()
     * @covers       \ExtendsSoftware\ExaPHP\Processor\Validator\Other\BetweenValidator::process()
     * @covers       \ExtendsSoftware\ExaPHP\Processor\Validator\Other\BetweenValidator::getTemplates()
     * @dataProvider invalidExclusiveDataProvider
     */
    public function testExclusive(int $integer): void
    {
        $validator = new BetweenValidator(1, 10, false);
        $result = $validator->process($integer);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Not numeric.
     *
     * Test that the value is not numeric and validate will not validate.
     *
     * @covers       \ExtendsSoftware\ExaPHP\Processor\Validator\Other\BetweenValidator::__construct()
     * @covers       \ExtendsSoftware\ExaPHP\Processor\Validator\Other\BetweenValidator::process()
     * @covers       \ExtendsSoftware\ExaPHP\Processor\Validator\Other\BetweenValidator::getTemplates()
     */
    public function testNotNumeric(): void
    {
        $validator = new BetweenValidator(2);
        $result = $validator->process('a');

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Internal validator.
     *
     * Test that internal validator will validate values and allow for a different type of values.
     *
     * @covers       \ExtendsSoftware\ExaPHP\Processor\Validator\Other\BetweenValidator::__construct()
     * @covers       \ExtendsSoftware\ExaPHP\Processor\Validator\Other\BetweenValidator::process()
     * @covers       \ExtendsSoftware\ExaPHP\Processor\Validator\Other\BetweenValidator::getTemplates()
     */
    public function testInternalValidator(): void
    {
        $validator = new BetweenValidator('2023-01-01', '2023-12-31', processor: new DateTimeValidator('Y-m-d'));

        $result1 = $validator->process('2023-10-10');
        $this->assertInstanceOf(ValidResult::class, $result1);
        $this->assertSame('2023-10-10', $result1->getValue());

        $result2 = $validator->process('2022-10-10');
        $this->assertInstanceOf(InvalidResult::class, $result2);

        $result3 = $validator->process('2024-10-10');
        $this->assertInstanceOf(InvalidResult::class, $result3);
    }
}
