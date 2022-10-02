<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell\Definition\Operand;

class Operand implements OperandInterface
{
    /**
     * Create new OperandInterface with name.
     *
     * @param string $name
     */
    public function __construct(private readonly string $name)
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
