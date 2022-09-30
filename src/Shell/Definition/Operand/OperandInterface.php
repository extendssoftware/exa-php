<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell\Definition\Operand;

interface OperandInterface
{
    /**
     * Get operand name.
     *
     * @return string
     */
    public function getName(): string;
}
