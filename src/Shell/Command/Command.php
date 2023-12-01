<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell\Command;

use ExtendsSoftware\ExaPHP\Shell\Definition\DefinitionInterface;

readonly class Command implements CommandInterface
{
    /**
     * Create new command for name with description and definition.
     *
     * @param string              $name
     * @param string              $description
     * @param DefinitionInterface $definition
     * @param mixed[]             $parameters
     */
    public function __construct(
        private string $name,
        private string $description,
        private DefinitionInterface $definition,
        private ?array $parameters = null
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @inheritDoc
     */
    public function getDefinition(): DefinitionInterface
    {
        return $this->definition;
    }

    /**
     * @inheritDoc
     */
    public function getParameters(): array
    {
        return $this->parameters ?? [];
    }

    /**
     * @inheritDoc
     */
    public function getParameter(string $key, mixed $default = null): mixed
    {
        return $this->parameters[$key] ?? $default;
    }
}
