<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Color\LightCyan;

use ExtendsSoftware\ExaPHP\Console\Formatter\Color\ColorInterface;

class LightCyan implements ColorInterface
{
    /**
     * @const string
     */
    public const NAME = 'LightCyan';

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }
}
