<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Format\Bold;

use ExtendsSoftware\ExaPHP\Console\Formatter\Format\FormatInterface;

class Bold implements FormatInterface
{
    /**
     * @const string
     */
    public const string NAME = 'Bold';

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }
}
