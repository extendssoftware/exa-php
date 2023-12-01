<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Color\LightGreen;

use ExtendsSoftware\ExaPHP\Console\Formatter\Color\ColorInterface;

class LightGreen implements ColorInterface
{
    /**
     * @const string
     */
    public const NAME = 'LightGreen';

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }
}
