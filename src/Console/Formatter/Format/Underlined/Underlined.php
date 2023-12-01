<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Format\Underlined;

use ExtendsSoftware\ExaPHP\Console\Formatter\Format\FormatInterface;

class Underlined implements FormatInterface
{
    /**
     * @const string
     */
    public const NAME = 'Underlined';

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }
}
