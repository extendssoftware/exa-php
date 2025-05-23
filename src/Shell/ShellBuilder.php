<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell;

use ExtendsSoftware\ExaPHP\Console\Output\Posix\PosixOutput;
use ExtendsSoftware\ExaPHP\Shell\About\About;
use ExtendsSoftware\ExaPHP\Shell\Command\Command;
use ExtendsSoftware\ExaPHP\Shell\Command\CommandInterface;
use ExtendsSoftware\ExaPHP\Shell\Definition\Definition;
use ExtendsSoftware\ExaPHP\Shell\Definition\Operand\Operand;
use ExtendsSoftware\ExaPHP\Shell\Definition\Option\Exception\NoShortAndLongName;
use ExtendsSoftware\ExaPHP\Shell\Definition\Option\Option;
use ExtendsSoftware\ExaPHP\Shell\Descriptor\Descriptor;
use ExtendsSoftware\ExaPHP\Shell\Descriptor\DescriptorInterface;
use ExtendsSoftware\ExaPHP\Shell\Parser\ParserInterface;
use ExtendsSoftware\ExaPHP\Shell\Parser\Posix\PosixParser;
use ExtendsSoftware\ExaPHP\Shell\Suggester\SimilarText\SimilarTextSuggester;
use ExtendsSoftware\ExaPHP\Shell\Suggester\SuggesterInterface;

class ShellBuilder implements ShellBuilderInterface
{
    /**
     * Name of the shell.
     *
     * @var string|null
     */
    private ?string $name = null;

    /**
     * Command to run shell.
     *
     * @var string|null
     */
    private ?string $program = null;

    /**
     * Shell version.
     *
     * @var string|null
     */
    private ?string $version = null;

    /**
     * Shell descriptor.
     *
     * @var DescriptorInterface|null
     */
    private ?DescriptorInterface $descriptor = null;

    /**
     * Command suggester.
     *
     * @var SuggesterInterface|null
     */
    private ?SuggesterInterface $suggester = null;

    /**
     * Argument parser.
     *
     * @var ParserInterface|null
     */
    private ?ParserInterface $parser = null;

    /**
     * Commands.
     *
     * @var CommandInterface[]
     */
    private array $commands = [];

    /**
     * @inheritDoc
     */
    public function build(): ShellInterface
    {
        $shell = new Shell(
            $this->descriptor ?: new Descriptor(new PosixOutput()),
            $this->suggester ?: new SimilarTextSuggester(),
            $this->parser ?: new PosixParser(),
            new About(
                $this->name ?: 'ExaPHP Console',
                $this->program ?: 'exa',
                $this->version ?: '0.4.0'
            )
        );

        foreach ($this->commands as $command) {
            $shell->addCommand($command);
        }

        $this->reset();

        return $shell;
    }

    /**
     * Add command to shell.
     *
     * @param string       $name
     * @param string       $description
     * @param mixed[]|null $operands
     * @param mixed[]|null $options
     * @param mixed[]|null $parameters
     *
     * @return ShellBuilder
     * @throws NoShortAndLongName When both short and long name are not given.
     */
    public function addCommand(
        string $name,
        string $description,
        array $operands = null,
        array $options = null,
        array $parameters = null
    ): ShellBuilder {
        $definition = new Definition();
        foreach ($operands ?: [] as $operand) {
            $definition->addOperand(
                new Operand($operand['name'])
            );
        }

        foreach ($options ?: [] as $option) {
            $definition->addOption(
                new Option(
                    $option['name'],
                    $option['description'],
                    $option['short'] ?? null,
                    $option['long'] ?? null,
                    $option['flag'] ?? null,
                    $option['multiple'] ?? null
                )
            );
        }

        $this->commands[] = new Command($name, $description, $definition, $parameters);

        return $this;
    }

    /**
     * Set name of the shell.
     *
     * @param string|null $name
     *
     * @return ShellBuilder
     */
    public function setName(string $name = null): ShellBuilder
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set command to run shell.
     *
     * @param string|null $program
     *
     * @return ShellBuilder
     */
    public function setProgram(string $program = null): ShellBuilder
    {
        $this->program = $program;

        return $this;
    }

    /**
     * Set shell version.
     *
     * @param string|null $version
     *
     * @return ShellBuilder
     */
    public function setVersion(string $version = null): ShellBuilder
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Set shell descriptor.
     *
     * @param DescriptorInterface|null $descriptor
     *
     * @return ShellBuilder
     */
    public function setDescriptor(DescriptorInterface $descriptor = null): ShellBuilder
    {
        $this->descriptor = $descriptor;

        return $this;
    }

    /**
     * Set command suggester.
     *
     * @param SuggesterInterface|null $suggester
     *
     * @return ShellBuilder
     */
    public function setSuggester(SuggesterInterface $suggester = null): ShellBuilder
    {
        $this->suggester = $suggester;

        return $this;
    }

    /**
     * Set argument parser.
     *
     * @param ParserInterface|null $parser
     *
     * @return ShellBuilder
     */
    public function setParser(ParserInterface $parser = null): ShellBuilder
    {
        $this->parser = $parser;

        return $this;
    }

    /**
     * Reset builder after build.
     *
     * @return void
     */
    private function reset(): void
    {
        $this->name = null;
        $this->program = null;
        $this->version = null;
        $this->descriptor = null;
        $this->suggester = null;
        $this->parser = null;
        $this->commands = [];
    }
}
