<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Framework\ServiceLocator\Loader;

use ExtendsSoftware\ExaPHP\Console\Input\InputInterface;
use ExtendsSoftware\ExaPHP\Console\Input\Posix\PosixInput;
use ExtendsSoftware\ExaPHP\Console\Output\OutputInterface;
use ExtendsSoftware\ExaPHP\Console\Output\Posix\PosixOutput;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Invokable\InvokableResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;

class ConsoleConfigLoaderTest extends TestCase
{
    /**
     * Load.
     *
     * Test that loader returns correct array.
     *
     * @covers \ExtendsSoftware\ExaPHP\Console\Framework\ServiceLocator\Loader\ConsoleConfigLoader::load()
     */
    public function testLoad(): void
    {
        $loader = new ConsoleConfigLoader();

        $this->assertSame([
            ServiceLocatorInterface::class => [
                InvokableResolver::class => [
                    InputInterface::class => PosixInput::class,
                    OutputInterface::class => PosixOutput::class,
                ],
            ],
        ], $loader->load());
    }
}
