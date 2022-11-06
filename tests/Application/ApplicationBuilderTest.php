<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Application;

use ExtendsSoftware\ExaPHP\Application\Exception\FailedToLoadCache;
use ExtendsSoftware\ExaPHP\Application\Module\ModuleInterface;
use ExtendsSoftware\ExaPHP\Application\Module\Provider\ConditionProviderInterface;
use ExtendsSoftware\ExaPHP\Application\Module\Provider\ConfigProviderInterface;
use ExtendsSoftware\ExaPHP\Http\Middleware\Chain\MiddlewareChainInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorFactoryInterface;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Utility\Loader\LoaderInterface;
use LogicException;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class ApplicationBuilderTest extends TestCase
{
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
        $this->module = new class implements ModuleInterface, ConfigProviderInterface, ConditionProviderInterface {
            /**
             * @var LoaderInterface|null
             */
            protected ?LoaderInterface $loader;

            /**
             * @var bool
             */
            protected bool $conditioned = false;

            /**
             * @inheritDoc
             */
            public function isConditioned(): bool
            {
                return $this->conditioned;
            }

            /**
             * @inheritDoc
             */
            public function getConfig(): LoaderInterface
            {
                if ($this->loader) {
                    return $this->loader;
                }

                throw new LogicException('Can not load config from conditioned module.');
            }

            /**
             * @param LoaderInterface $loader
             *
             * @return ModuleInterface
             */
            public function setLoader(LoaderInterface $loader): ModuleInterface
            {
                $this->loader = $loader;

                return $this;
            }

            /**
             * @return ModuleInterface
             */
            public function setConditioned(): ModuleInterface
            {
                $this->conditioned = true;

                return $this;
            }
        };
    }

    /**
     * Build.
     *
     * Test that builder will load and cache config and build an instance of ApplicationInterface.
     *
     * @covers \ExtendsSoftware\ExaPHP\Application\ApplicationBuilder::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Application\ApplicationBuilder::addConfig()
     * @covers \ExtendsSoftware\ExaPHP\Application\ApplicationBuilder::addGlobalConfigDirectory()
     * @covers \ExtendsSoftware\ExaPHP\Application\ApplicationBuilder::setCacheLocation()
     * @covers \ExtendsSoftware\ExaPHP\Application\ApplicationBuilder::setCacheFilename()
     * @covers \ExtendsSoftware\ExaPHP\Application\ApplicationBuilder::setCacheEnabled()
     * @covers \ExtendsSoftware\ExaPHP\Application\ApplicationBuilder::setServiceLocatorFactory()
     * @covers \ExtendsSoftware\ExaPHP\Application\ApplicationBuilder::addModule()
     * @covers \ExtendsSoftware\ExaPHP\Application\ApplicationBuilder::build()
     * @covers \ExtendsSoftware\ExaPHP\Application\ApplicationBuilder::getConfig()
     * @covers \ExtendsSoftware\ExaPHP\Application\ApplicationBuilder::setFrameworkEnabled()
     * @covers \ExtendsSoftware\ExaPHP\Application\ApplicationBuilder::reset()
     */
    public function testBuild(): void
    {
        $root = vfsStream::setup('root', null, [
            'config' => [
                'global' => [
                    'global.php' => "<?php return ['foo' => 'bar'];",
                ],
                'local' => [
                    'local.php' => "<?php return ['local' => true];",
                ],
            ],
        ]);

        $loader = $this->createMock(LoaderInterface::class);
        $loader
            ->expects($this->once())
            ->method('load')
            ->willReturn([
                [
                    'enabled' => true,
                ],
            ]);

        $this->module->setLoader($loader);

        /**
         * @var LoaderInterface $loader
         */
        $builder = new ApplicationBuilder();
        $application = $builder
            ->setFrameworkEnabled(true)
            ->addConfig(
                new class implements LoaderInterface {
                    /**
                     * @inheritDoc
                     */
                    public function load(): array
                    {
                        return [
                            'global' => false,
                        ];
                    }
                }
            )
            ->addGlobalConfigDirectory($root->url() . '/config/global', '/(.*)?(local|global)\.php/i')
            ->addGlobalConfigDirectory($root->url() . '/config/local', '/(.*)?(local|global)\.php/i')
            ->setCacheLocation($root->url() . '/config')
            ->setCacheFilename('application.cache')
            ->setCacheEnabled(true)
            ->addModule($this->module)
                ->addModule((clone $this->module)->setConditioned())
            ->build();

        $this->assertIsObject($application);

        $container = include $root->url() . '/config/application.cache.php';

        $this->assertArrayHasKey(ServiceLocatorInterface::class, $container);
        $this->assertArrayHasKey(MiddlewareChainInterface::class, $container);

        $this->assertFalse($container['global']);
        $this->assertSame('bar', $container['foo']);
        $this->assertTrue($container['enabled']);
    }

    /**
     * Cached.
     *
     * Test that cached config is returned.
     *
     * @covers \ExtendsSoftware\ExaPHP\Application\ApplicationBuilder::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Application\ApplicationBuilder::setCacheFilename()
     * @covers \ExtendsSoftware\ExaPHP\Application\ApplicationBuilder::setCacheEnabled()
     * @covers \ExtendsSoftware\ExaPHP\Application\ApplicationBuilder::setServiceLocatorFactory()
     * @covers \ExtendsSoftware\ExaPHP\Application\ApplicationBuilder::addModule()
     * @covers \ExtendsSoftware\ExaPHP\Application\ApplicationBuilder::build()
     * @covers \ExtendsSoftware\ExaPHP\Application\ApplicationBuilder::getConfig()
     * @covers \ExtendsSoftware\ExaPHP\Application\ApplicationBuilder::reset()
     */
    public function testCached(): void
    {
        $root = vfsStream::setup('root', null, [
            'config' => [
                'application.cache.php' => "<?php return ['foo' => 'bar'];",
            ],
        ]);

        $loader = $this->createMock(LoaderInterface::class);
        $loader
            ->expects($this->never())
            ->method('load');

        $this->module->setConditioned();
        $this->module->setLoader($loader);

        $serviceLocator = $this->createMock(ServiceLocatorInterface::class);
        $serviceLocator
            ->expects($this->once())
            ->method('getService')
            ->with(ApplicationInterface::class)
            ->willReturn($this->createMock(ApplicationInterface::class));

        $factory = $this->createMock(ServiceLocatorFactoryInterface::class);
        $factory
            ->expects($this->once())
            ->method('create')
            ->willReturn($serviceLocator);

        /**
         * @var ServiceLocatorFactoryInterface $factory
         */
        $builder = new ApplicationBuilder();
        $application = $builder
            ->setCacheLocation($root->url() . '/config')
            ->setCacheEnabled(true)
            ->setCacheFilename('application.cache')
            ->setServiceLocatorFactory($factory)
            ->addModule($this->module)
            ->build();

        $this->assertIsObject($application);
    }

    /**
     * Cache location missing.
     *
     * Test that an exception is thrown when cache location is missing.
     *
     * @covers \ExtendsSoftware\ExaPHP\Application\ApplicationBuilder::__construct()
     * @covers \ExtendsSoftware\ExaPHP\Application\ApplicationBuilder::setCacheEnabled()
     * @covers \ExtendsSoftware\ExaPHP\Application\ApplicationBuilder::build()
     * @covers \ExtendsSoftware\ExaPHP\Application\ApplicationBuilder::getConfig()
     * @covers \ExtendsSoftware\ExaPHP\Application\Exception\CacheLocationMissing::__construct
     * @covers \ExtendsSoftware\ExaPHP\Application\Exception\FailedToLoadCache::__construct
     */
    public function testCacheLocationMissing(): void
    {
        $this->expectException(FailedToLoadCache::class);
        $this->expectExceptionMessage('Failed to load config. See previous exception for more details.');

        $builder = new ApplicationBuilder();
        $builder
            ->setCacheEnabled(true)
            ->build();
    }
}
