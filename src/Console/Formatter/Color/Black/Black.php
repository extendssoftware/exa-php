<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Color\Black;

use ExtendsSoftware\ExaPHP\Console\Formatter\Color\ColorInterface;

class Black implements ColorInterface
{
    /**
     * @const string
     */
    public const string NAME = 'Black';

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }
}
