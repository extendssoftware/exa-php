<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Priority\Debug;

use ExtendsSoftware\ExaPHP\Logger\Priority\PriorityInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class DebugPriorityTest extends TestCase
{
    /**
     * Get methods.
     *
     * Test that get methods will return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Logger\Priority\Debug\DebugPriority::getValue()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Priority\Debug\DebugPriority::getKeyword()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Priority\Debug\DebugPriority::getDescription()
     */
    public function testGetMethods(): void
    {
        $priority = new DebugPriority();

        $this->assertSame(7, $priority->getValue());
        $this->assertSame('debug', $priority->getKeyword());
        $this->assertSame('Debug-level messages.', $priority->getDescription());
    }

    /**
     * Factory.
     *
     * Test that factory methods returns an instanceof PriorityInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Logger\Priority\Debug\DebugPriority::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $priority = DebugPriority::factory('AbstractPriority', $serviceLocator, []);

        $this->assertInstanceOf(PriorityInterface::class, $priority);
    }
}
