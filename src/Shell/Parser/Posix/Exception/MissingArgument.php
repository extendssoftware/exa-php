<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell\Parser\Posix\Exception;

use Exception;
use ExtendsSoftware\ExaPHP\Shell\Definition\Option\OptionInterface;
use ExtendsSoftware\ExaPHP\Shell\Parser\ParserException;

class MissingArgument extends Exception implements ParserException
{
    /**
     * Required option has no argument.
     *
     * @param OptionInterface $option
     * @param bool|null       $long
     */
    public function __construct(OptionInterface $option, bool $long = null)
    {
        parent::__construct(
            sprintf(
                '%s option "%s%s" requires an argument, non given.',
                $long ? 'Long' : 'Short',
                $long ? '--' : '-',
                $long ? $option->getLong() : $option->getShort()
            )
        );
    }
}
