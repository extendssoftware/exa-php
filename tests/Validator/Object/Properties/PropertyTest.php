<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Object\Properties;

use ExtendsSoftware\ExaPHP\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

class PropertyTest extends TestCase
{
    /**
     * Get methods.
     *
     * Test that get methods will return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\Properties\Property::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\Properties\Property::getName()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\Properties\Property::getValidator()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Object\Properties\Property::isOptional()
     */
    public function testGetMethods(): void
    {
        $validator = $this->createMock(ValidatorInterface::class);

        /**
         * @var ValidatorInterface $validator
         */
        $property = new Property('foo', $validator);

        $this->assertSame('foo', $property->getName());
        $this->assertSame($validator, $property->getValidator());
        $this->assertFalse($property->isOptional());
    }
}
