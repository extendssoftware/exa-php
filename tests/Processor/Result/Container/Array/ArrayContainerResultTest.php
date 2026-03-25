<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Processor\Result\Container\Array;

use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use PHPUnit\Framework\TestCase;

class ArrayContainerResultTest extends TestCase
{
    /**
     * Get value.
     *
     * Tes that method will return value.
     *
     * @covers \ExtendsSoftware\ExaPHP\Processor\Result\Container\Array\ArrayContainerResult::addItem()
     * @covers \ExtendsSoftware\ExaPHP\Processor\Result\Container\Array\ArrayContainerResult::getValue()
     */
    public function testGetValue(): void
    {
        $result1 = $this->createMock(ResultInterface::class);
        $result1
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $result1
            ->expects($this->once())
            ->method('getValue')
            ->willReturn('Result 1 value');

        $result2 = $this->createMock(ResultInterface::class);
        $result2
            ->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        $result2
            ->expects($this->once())
            ->method('getValue')
            ->willReturn('Result 2 value');

        $container = new ArrayContainerResult();
        $container
            ->addItem($result1)
            ->addItem($result2);

        $this->assertSame([
            'Result 1 value',
            'Result 2 value',
        ], $container->getValue());
    }
}
