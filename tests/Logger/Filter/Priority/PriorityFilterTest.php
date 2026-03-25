<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Filter\Priority;

use ExtendsSoftware\ExaPHP\Logger\Filter\FilterInterface;
use ExtendsSoftware\ExaPHP\Logger\LogInterface;
use ExtendsSoftware\ExaPHP\Logger\Priority\PriorityInterface;
use ExtendsSoftware\ExaPHP\Processor\ProcessorInterface;
use ExtendsSoftware\ExaPHP\Processor\Result\ResultInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class PriorityFilterTest extends TestCase
{
    /**
     * Filter.
     *
     * Test that filter returns true when validator is valid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Logger\Filter\Priority\PriorityFilter::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Filter\Priority\PriorityFilter::filter()
     */
    public function testFilter(): void
    {
        $priority = $this->createMock(PriorityInterface::class);
        $priority
            ->expects($this->once())
            ->method('getValue')
            ->willReturn(3);

        $log = $this->createMock(LogInterface::class);
        $log
            ->expects($this->once())
            ->method('getPriority')
            ->willReturn($priority);

        /**
         * @var LogInterface $log
         */
        $filter = new PriorityFilter();

        $this->assertTrue($filter->filter($log));
    }

    /**
     * Do not filter.
     *
     * Test that filter returns false when validator is invalid.
     *
     * @covers \ExtendsSoftware\ExaPHP\Logger\Filter\Priority\PriorityFilter::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Filter\Priority\PriorityFilter::filter()
     */
    public function testDoNotFilter(): void
    {
        $priority = $this->createMock(PriorityInterface::class);
        $priority
            ->method('getValue')
            ->willReturn(3);

        $result = $this->createMock(ResultInterface::class);
        $result
            ->method('isValid')
            ->willReturn(false);

        $validator = $this->createMock(ProcessorInterface::class);
        $validator
            ->method('process')
            ->with(3)
            ->willReturn($result);

        $log = $this->createMock(LogInterface::class);
        $log
            ->method('getPriority')
            ->willReturn($priority);

        /**
         * @var LogInterface       $log
         * @var PriorityInterface  $priority
         * @var ProcessorInterface $validator
         */
        $filter = new PriorityFilter($priority, $validator);

        $this->assertFalse($filter->filter($log));
    }

    /**
     * Factory.
     *
     * Test that create method will return an FilterInterface instance.
     *
     * @covers \ExtendsSoftware\ExaPHP\Logger\Filter\Priority\PriorityFilter::factory()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Filter\Priority\PriorityFilter::__construct()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->exactly(2))
            ->method('getService')
            ->willReturnCallback(fn($key) => match ([$key]) {
                [PriorityInterface::class] => $this->createMock(PriorityInterface::class),
                [ProcessorInterface::class] => $this->createMock(ProcessorInterface::class),
            });

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $filter = PriorityFilter::factory(PriorityFilter::class, $serviceLocator, [
            'priority' => [
                'name' => PriorityInterface::class,
            ],
            'processor' => [
                'name' => ProcessorInterface::class,
            ],
        ]);

        $this->assertInstanceOf(FilterInterface::class, $filter);
    }
}
