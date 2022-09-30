<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Priority\Critical;

use ExtendsSoftware\ExaPHP\Logger\Priority\PriorityInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class CriticalPriorityTest extends TestCase
{
    /**
     * Get methods.
     *
     * Test that get methods will return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Logger\Priority\Critical\CriticalPriority::getValue()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Priority\Critical\CriticalPriority::getKeyword()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Priority\Critical\CriticalPriority::getDescription()
     */
    public function testGetMethods(): void
    {
        $priority = new CriticalPriority();

        $this->assertSame(2, $priority->getValue());
        $this->assertSame('crit', $priority->getKeyword());
        $this->assertSame('Critical conditions, such as hard device errors.', $priority->getDescription());
    }

    /**
     * Factory.
     *
     * Test that factory methods returns an instanceof PriorityInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Logger\Priority\Critical\CriticalPriority::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $priority = CriticalPriority::factory('AbstractPriority', $serviceLocator, []);

        $this->assertInstanceOf(PriorityInterface::class, $priority);
    }
}
