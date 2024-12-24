<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell\Exception;

use Exception;
use ExtendsSoftware\ExaPHP\Shell\ShellException;

use function sprintf;

class CommandNotFound extends Exception implements ShellException
{
    /**
     * Command not found for name.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct(
            sprintf(
                'Command "%s" not found.',
                $name
            )
        );
    }
}
