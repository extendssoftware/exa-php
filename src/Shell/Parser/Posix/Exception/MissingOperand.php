<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell\Parser\Posix\Exception;

use Exception;
use ExtendsSoftware\ExaPHP\Shell\Parser\ParserException;

class MissingOperand extends Exception implements ParserException
{
    /**
     * Required operand is missing.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct(
            sprintf(
                'Operand "%s" is required.',
                $name
            )
        );
    }
}
