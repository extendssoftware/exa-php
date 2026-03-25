<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Other\Object;

use ExtendsSoftware\ExaPHP\Processor\Result\Invalid\InvalidResult;
use ExtendsSoftware\ExaPHP\Processor\Result\Valid\ValidResult;
use PHPUnit\Framework\TestCase;

class PropertyProcessorTest extends TestCase
{
    /**
     * Valid.
     *
     * Test that an object property exists.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyProcessor::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyProcessor::process()
     */
    public function testValid(): void
    {
        $object = (object)[
            'foo' => 'bar',
        ];

        $processor = new PropertyProcessor('foo');
        $result = $processor->process($object);

        $this->assertInstanceOf(ValidResult::class, $result);
        $this->assertSame($object, $result->getValue());
    }

    /**
     * Invalid.
     *
     * Test that object property does not exist.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyProcessor::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyProcessor::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyProcessor::getTemplates()
     */
    public function testInvalid(): void
    {
        $processor = new PropertyProcessor('foo');
        $result = $processor->process((object)[
            'bar' => 'foo',
        ]);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }

    /**
     * Not object.
     *
     * Test that a value is not an object.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyProcessor::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyProcessor::process()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Other\Object\PropertyProcessor::getTemplates()
     */
    public function testNotObject(): void
    {
        $processor = new PropertyProcessor('foo');
        $result = $processor->process([
            'bar' => 'foo',
        ]);

        $this->assertInstanceOf(InvalidResult::class, $result);
    }
}
