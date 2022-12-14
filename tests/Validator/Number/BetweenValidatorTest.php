<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Number;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class BetweenValidatorTest extends TestCase
{
    /**
     * Valid inclusive values.
     *
     * @return array<array<float>>
     */
    public function validInclusiveDataProvider(): array
    {
        return [
            [1.0],
            [1],
            [1.1],
            [5],
            [9.9],
            [10],
            [10.0],
        ];
    }

    /**
     * Invalid inclusive values.
     *
     * @return array<array<float>>
     */
    public function invalidInclusiveDataProvider(): array
    {
        return [
            [0],
            [0.9],
            [10.1],
            [11],
        ];
    }

    /**
     * Invalid exclusive values.
     *
     * @return array<array<float>>
     */
    public function invalidExclusiveDataProvider(): array
    {
        return [
            [1],
            [1.0],
            [10.0],
            [10],
        ];
    }

    /**
     * Valid.
     *
     * Test that values are valid.
     *
     * @param float|int $number
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::__construct()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::validate()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::getTemplates()
     * @dataProvider validInclusiveDataProvider
     */
    public function testValid(float|int $number): void
    {
        $validator = new BetweenValidator(1, 10);

        $this->assertTrue($validator->validate($number)->isValid());
    }

    /**
     * Invalid.
     *
     * Test that values are invalid.
     *
     * @param float|int $number
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::__construct()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::validate()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::getTemplates()
     * @dataProvider invalidInclusiveDataProvider
     */
    public function testInvalid(float|int $number): void
    {
        $validator = new BetweenValidator(1, 10);

        $this->assertFalse($validator->validate($number)->isValid());
    }

    /**
     * Exclusive.
     *
     * Test that bound values are invalid when inclusive is false.
     *
     * @param float|int $number
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::__construct()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::validate()
     * @covers       \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::getTemplates()
     * @dataProvider invalidExclusiveDataProvider
     */
    public function testExclusive(float|int $number): void
    {
        $validator = new BetweenValidator(1, 10, false);

        $this->assertFalse($validator->validate($number)->isValid());
    }

    /**
     * Not numeric.
     *
     * Test that value is not numeric and validate will not validate.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::validate()
     */
    public function testNotNumeric(): void
    {
        $validator = new BetweenValidator(2);
        $result = $validator->validate('a');

        $this->assertFalse($result->isValid());
    }

    /**
     * Factory.
     *
     * Test that factory returns correct instance.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Number\BetweenValidator::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = BetweenValidator::factory(BetweenValidator::class, $serviceLocator, [
            'min' => 1,
            'max' => 10,
            'inclusive' => false,
        ]);

        $this->assertInstanceOf(BetweenValidator::class, $validator);
    }
}
