<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Color\Cyan;

use ExtendsSoftware\ExaPHP\Console\Formatter\Color\ColorInterface;

class Cyan implements ColorInterface
{
    /**
     * @const string
     */
    public const NAME = 'Cyan';

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }
}
