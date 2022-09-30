<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Priority\Emergency;

use ExtendsSoftware\ExaPHP\Logger\Priority\PriorityInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class EmergencyPriorityTest extends TestCase
{
    /**
     * Get methods.
     *
     * Test that get methods will return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Logger\Priority\Emergency\EmergencyPriority::getValue()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Priority\Emergency\EmergencyPriority::getKeyword()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Priority\Emergency\EmergencyPriority::getDescription()
     */
    public function testGetMethods(): void
    {
        $priority = new EmergencyPriority();

        $this->assertSame(0, $priority->getValue());
        $this->assertSame('emerg', $priority->getKeyword());
        $this->assertSame('System is unusable.', $priority->getDescription());
    }

    /**
     * Factory.
     *
     * Test that factory methods returns an instanceof PriorityInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Logger\Priority\Emergency\EmergencyPriority::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $priority = EmergencyPriority::factory('AbstractPriority', $serviceLocator, []);

        $this->assertInstanceOf(PriorityInterface::class, $priority);
    }
}
