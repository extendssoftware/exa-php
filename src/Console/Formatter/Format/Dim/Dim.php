<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Format\Dim;

use ExtendsSoftware\ExaPHP\Console\Formatter\Format\FormatInterface;

class Dim implements FormatInterface
{
    /**
     * @const string
     */
    public const NAME = 'Dim';

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }
}
