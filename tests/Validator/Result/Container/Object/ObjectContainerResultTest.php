<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Validator\Result\Container\Object;

use ExtendsSoftware\ExaPHP\Validator\Result\ResultInterface;
use PHPUnit\Framework\TestCase;

class ObjectContainerResultTest extends TestCase
{
    /**
     * Get value.
     *
     * Tes that method will return value.
     *
     * @covers \ExtendsSoftware\ExaPHP\Validator\Result\Container\Object\ObjectContainerResult::addProperty()
     * @covers \ExtendsSoftware\ExaPHP\Validator\Result\Container\Object\ObjectContainerResult::getValue()
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

        $container = new ObjectContainerResult();
        $container
            ->addProperty('result1', $result1)
            ->addProperty('result2', $result2);

        $this->assertEquals(
            (object)[
                'result1' => 'Result 1 value',
                'result2' => 'Result 2 value',
            ],
            $container->getValue()
        );
    }
}
