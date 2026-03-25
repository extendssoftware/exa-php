<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Other\Object\Properties;

use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;
use PHPUnit\Framework\TestCase;

class PropertyTest extends TestCase
{
    /**
     * Get methods.
     *
     * Test that get methods will return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\Properties\Property::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\Properties\Property::getValue()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\Properties\Property::getProcessor()
     */
    public function testGetMethods(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);

        $property = new Property('foo', $processor);

        $this->assertSame('foo', $property->getValue());
        $this->assertSame($processor, $property->getProcessor());
    }
}
