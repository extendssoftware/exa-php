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

    /**
     * Get parameter.
     *
     * @param string     $key     The key to get value for.
     * @param mixed|null $default Default value to return when key not exists.
     *
     * @return mixed
     */
    public function getParameter(string $key, mixed $default = null): mixed;
}
