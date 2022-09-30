<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Identity\Framework\ServiceLocator\Factory;

use ExtendsSoftware\ExaPHP\Identity\Storage\StorageInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class StorageFactoryTest extends TestCase
{
    /**
     * Create service.
     *
     * Test that instance of StorageInterface will be created.
     *
     * @covers \ExtendsSoftware\ExaPHP\Identity\Framework\ServiceLocator\Factory\StorageFactory::createService()
     */
    public function testCreateService(): void
    {
        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        /**
         * @var ServiceLocatorInterface $serviceLocator
         */
        $factory = new StorageFactory();
        $storage = $factory->createService(StorageInterface::class, $serviceLocator);

        $this->assertInstanceOf(StorageInterface::class, $storage);
    }
}
