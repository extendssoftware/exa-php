<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Collection;

use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class InArrayValidatorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that value exist in array and a valid result will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\InArrayValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\InArrayValidator::validate()
     */
    public function testValid(): void
    {
        $validator = new InArrayValidator([
            'foo',
            'bar',
            'baz',
        ]);
        $result = $validator->validate('foo');

        $this->assertTrue($result->isValid());
    }

    /**
     * Not in array.
     *
     * Test that value not exist in array and an invalid result will be returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\InArrayValidator::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\InArrayValidator::validate()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\InArrayValidator::getTemplates()
     */
    public function testInvalid(): void
    {
        $validator = new InArrayValidator([
            'foo',
            'bar',
            'baz',
        ]);
        $result = $validator->validate('qux');

        $this->assertFalse($result->isValid());
    }

    /**
     * Factory.
     *
     * Test that factory returns an instanceof of ValidatorInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Collection\InArrayValidator::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $validator = InArrayValidator::factory(InArrayValidator::class, $serviceLocator, [
            'values' => [
                'foo',
                'bar',
                'baz',
            ]
        ]);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }
}
