<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell;

use ExtendsSoftware\ExaPHP\Shell\About\AboutInterface;
use ExtendsSoftware\ExaPHP\Shell\Command\CommandInterface;
use ExtendsSoftware\ExaPHP\Shell\Definition\Definition;
use ExtendsSoftware\ExaPHP\Shell\Definition\DefinitionException;
use ExtendsSoftware\ExaPHP\Shell\Definition\DefinitionInterface;
use ExtendsSoftware\ExaPHP\Shell\Definition\Option\Option;
use ExtendsSoftware\ExaPHP\Shell\Descriptor\DescriptorInterface;
use ExtendsSoftware\ExaPHP\Shell\Exception\CommandNotFound;
use ExtendsSoftware\ExaPHP\Shell\Parser\ParserException;
use ExtendsSoftware\ExaPHP\Shell\Parser\ParserInterface;
use ExtendsSoftware\ExaPHP\Shell\Suggester\SuggesterInterface;

class Shell implements ShellInterface
{
    /**
     * Shell definition for global options.
     *
     * @var DefinitionInterface
     */
    private DefinitionInterface $definition;

    /**
     * Commands to iterate.
     *
     * @var CommandInterface[]
     */
    private array $commands = [];

    /**
     * Create a new Shell.
     *
     * @param DescriptorInterface $descriptor
     * @param SuggesterInterface  $suggester
     * @param ParserInterface     $parser
     * @param AboutInterface      $about
     */
    public function __construct(
        private readonly DescriptorInterface $descriptor,
        private readonly SuggesterInterface  $suggester,
        private readonly ParserInterface     $parser,
        private readonly AboutInterface      $about
    ) {
        $this->definition = (new Definition())
            ->addOption(new Option('verbose', 'Be more verbose.', 'v', 'verbose', isMultiple: true))
            ->addOption(new Option('help', 'Show help about shell or command.', 'h', 'help'));
    }

    /**
     * @inheritDoc
     */
    public function process(array $arguments): ?ShellResultInterface
    {
        try {
            $defaults = $this->parser->parse($this->definition, $arguments, false);
        } catch (ParserException|DefinitionException $exception) {
            $this->descriptor
                ->exception($exception)
                ->shell($this->about, $this->definition, $this->commands, true);

            return null;
        }

        $remaining = $defaults->getRemaining();
        $parsed = $defaults->getParsed();

        $this->descriptor->setVerbosity($parsed['verbose'] ?? 1);

        $name = array_shift($remaining);
        if ($name === null) {
            $this->descriptor->shell($this->about, $this->definition, $this->commands);

            return null;
        }

        try {
            $command = $this->getCommand($name);
        } catch (CommandNotFound $exception) {
            $this->descriptor
                ->exception($exception)
                ->suggest(
                    $this->suggester->suggest($name, ...$this->commands)
                )
                ->shell($this->about, $this->definition, $this->commands, true);

            return null;
        }

        $help = $parsed['help'] ?? false;
        if ($help) {
            $this->descriptor->command($this->about, $command);

            return null;
        }

        try {
            $result = $this->parser->parse(
                $command->getDefinition(),
                $remaining
            );

            return new ShellResult(
                $command,
                $result->getParsed()
            );
        } catch (ParserException|DefinitionException $exception) {
            $this->descriptor
                ->exception($exception)
                ->command($this->about, $command, true);

            return null;
        }
    }

    /**
     * Add command to shell.
     *
     * Commands will be processed in chronological order.
     *
     * @param CommandInterface $command
     *
     * @return Shell
     */
    public function addCommand(CommandInterface $command): Shell
    {
        $this->commands[] = $command;

        return $this;
    }

    /**
     * Get command with name.
     *
     * @param string $name
     *
     * @return CommandInterface
     * @throws CommandNotFound When command can not be found.
     */
    private function getCommand(string $name): CommandInterface
    {
        foreach ($this->commands as $command) {
            if ($command->getName() === $name) {
                return $command;
            }
        }

        throw new CommandNotFound($name);
    }
}
