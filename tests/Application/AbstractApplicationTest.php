<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application;

use ExtendsSoftware\ExaPHP\Application\Module\ModuleInterface;
use ExtendsSoftware\ExaPHP\Application\Module\Provider\ShutdownProviderInterface;
use ExtendsSoftware\ExaPHP\Application\Module\Provider\StartupProviderInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class AbstractApplicationTest extends TestCase
{
    /**
     * Dummy abstract application.
     *
     * @var AbstractApplication
     */
    private $application;

    /**
     * Dummy module.
     *
     * @var ModuleInterface
     */
    private $module;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->module = new class implements ModuleInterface, StartupProviderInterface, ShutdownProviderInterface {
            /**
             * @var bool
             */
            protected bool $startup = false;

            /**
             * @var bool
             */
            protected bool $shutdown = false;

            /**
             * @inheritDoc
             */
            public function onStartup(ServiceLocatorInterface $serviceLocator): void
            {
                $this->startup = true;
            }

            /**
             * @inheritDoc
             */
            public function onShutdown(ServiceLocatorInterface $serviceLocator): void
            {
                $this->shutdown = true;
            }

            /**
             * @return bool
             */
            public function isStartup(): bool
            {
                return $this->startup;
            }

            /**
             * @return bool
             */
            public function isShutdown(): bool
            {
                return $this->shutdown;
            }
        };

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);

        $this->application = new class($serviceLocator, [$this->module]) extends AbstractApplication {
            /**
             * @inheritDoc
             */
            protected function run(): AbstractApplication
            {
                return $this;
            }
        };
    }


    /**
     * Bootstrap.
     *
     * Test that modules will be bootstrapped by application.
     *
     * @covers \ExtendsSoftware\ExaPHP\Application\AbstractApplication::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Application\AbstractApplication::bootstrap()
     * @covers \ExtendsSoftware\ExaPHP\Application\AbstractApplication::getServiceLocator()
     */
    public function testBootstrap(): void
    {
        $this->application->bootstrap();

        $this->assertTrue($this->module->isStartup());
        $this->assertTrue($this->module->isShutdown());
    }
}
