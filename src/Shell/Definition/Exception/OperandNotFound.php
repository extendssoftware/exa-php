<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell\Definition\Exception;

use Exception;
use ExtendsSoftware\ExaPHP\Shell\Definition\DefinitionException;

use function sprintf;

class OperandNotFound extends Exception implements DefinitionException
{
    /**
     * No operand for position.
     *
     * @param int $position
     */
    public function __construct(int $position)
    {
        parent::__construct(
            sprintf(
                'No operand found for position "%s".',
                $position
            )
        );
    }
}
