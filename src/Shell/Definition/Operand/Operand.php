<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell\Definition\Operand;

readonly class Operand implements OperandInterface
{
    /**
     * Create new OperandInterface with name.
     *
     * @param string $name
     */
    public function __construct(private string $name)
    {
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }
}
