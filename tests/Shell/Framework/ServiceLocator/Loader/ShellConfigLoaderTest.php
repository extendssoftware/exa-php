<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell\Framework\ServiceLocator\Loader;

use ExtendsSoftware\ExaPHP\Console\Input\InputInterface;
use ExtendsSoftware\ExaPHP\Console\Input\Posix\PosixInput;
use ExtendsSoftware\ExaPHP\Console\Output\OutputInterface;
use ExtendsSoftware\ExaPHP\Console\Output\Posix\PosixOutput;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Alias\AliasResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Factory\FactoryResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Invokable\InvokableResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\Resolver\Reflection\ReflectionResolver;
use ExtendsSoftware\ExaPHP\ServiceLocator\ServiceLocatorInterface;
use ExtendsSoftware\ExaPHP\Shell\Descriptor\Descriptor;
use ExtendsSoftware\ExaPHP\Shell\Descriptor\DescriptorInterface;
use ExtendsSoftware\ExaPHP\Shell\Framework\ServiceLocator\Factory\ShellFactory;
use ExtendsSoftware\ExaPHP\Shell\Parser\ParserInterface;
use ExtendsSoftware\ExaPHP\Shell\Parser\Posix\PosixParser;
use ExtendsSoftware\ExaPHP\Shell\ShellInterface;
use ExtendsSoftware\ExaPHP\Shell\Suggester\SimilarText\SimilarTextSuggester;
use ExtendsSoftware\ExaPHP\Shell\Suggester\SuggesterInterface;
use PHPUnit\Framework\TestCase;

class ShellConfigLoaderTest extends TestCase
{
    /**
     * Load.
     *
     * Test that loader returns correct array.
     *
     * @covers \ExtendsSoftware\ExaPHP\Shell\Framework\ServiceLocator\Loader\ShellConfigLoader::load()
     */
    public function testLoad(): void
    {
        $loader = new ShellConfigLoader();

        $this->assertSame([
            ServiceLocatorInterface::class => [
                FactoryResolver::class => [
                    ShellInterface::class => ShellFactory::class,
                ],
                InvokableResolver::class => [
                    SuggesterInterface::class => SimilarTextSuggester::class,
                    ParserInterface::class => PosixParser::class,
                    OutputInterface::class => PosixOutput::class,
                    InputInterface::class => PosixInput::class,
                ],
                ReflectionResolver::class => [
                    DescriptorInterface::class => Descriptor::class,
                ],
            ],
        ], $loader->load());
    }
}
