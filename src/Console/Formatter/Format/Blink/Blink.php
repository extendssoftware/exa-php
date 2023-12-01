<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Format\Blink;

use ExtendsSoftware\ExaPHP\Console\Formatter\Format\FormatInterface;

class Blink implements FormatInterface
{
    /**
     * @const string
     */
    public const NAME = 'Blink';

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }
}
