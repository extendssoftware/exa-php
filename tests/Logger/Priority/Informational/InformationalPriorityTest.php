<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Priority\Informational;

use ExtendsSoftware\ExaPHP\Logger\Priority\PriorityInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class InformationalPriorityTest extends TestCase
{
    /**
     * Get methods.
     *
     * Test that get methods will return correct values.
     *
     * @covers \ExtendsSoftware\ExaPHP\Logger\Priority\Informational\InformationalPriority::getValue()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Priority\Informational\InformationalPriority::getKeyword()
     * @covers \ExtendsSoftware\ExaPHP\Logger\Priority\Informational\InformationalPriority::getDescription()
     */
    public function testGetMethods(): void
    {
        $priority = new InformationalPriority();

        $this->assertSame(6, $priority->getValue());
        $this->assertSame('info', $priority->getKeyword());
        $this->assertSame('Informational messages.', $priority->getDescription());
    }

    /**
     * Factory.
     *
     * Test that factory methods returns an instanceof PriorityInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Logger\Priority\Informational\InformationalPriority::factory()
     */
    public function testFactory(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $priority = InformationalPriority::factory('AbstractPriority', $serviceLocator, []);

        $this->assertInstanceOf(PriorityInterface::class, $priority);
    }
}
