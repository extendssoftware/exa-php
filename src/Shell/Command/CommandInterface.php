<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell\Command;

use ExtendsSoftware\ExaPHP\Shell\Definition\DefinitionInterface;

interface CommandInterface
{
    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * Get definition.
     *
     * @return DefinitionInterface
     */
    public function getDefinition(): DefinitionInterface;

    /**
     * Get parameters.
     *
     * @return mixed[]
     */
    public function getParameters(): array;
}
