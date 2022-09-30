<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Priority\Alert;

use ExtendsSoftware\ExaPHP\Logger\Priority\PriorityInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class AlertPriorityTest extends TestCase
{
    /**
     * Get methods.
     *
     * Test that get methods will return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Logger\Priority\Alert\AlertPriority::getValue()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Priority\Alert\AlertPriority::getKeyword()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Priority\Alert\AlertPriority::getDescription()
     */
    public function testGetMethods(): void
    {
        $priority = new AlertPriority();

        $this->assertSame(1, $priority->getValue());
        $this->assertSame('alert', $priority->getKeyword());
        $this->assertSame('Action must be taken immediately.', $priority->getDescription());
    }

    /**
     * Factory.
     *
     * Test that factory methods returns an instanceof PriorityInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Logger\Priority\Alert\AlertPriority::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $priority = AlertPriority::factory('AlertPriority', $serviceLocator, []);

        $this->assertInstanceOf(PriorityInterface::class, $priority);
    }
}
