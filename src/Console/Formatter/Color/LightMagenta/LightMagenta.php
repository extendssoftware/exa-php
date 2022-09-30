<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Color\LightMagenta;

use ExtendsSoftware\ExaPHP\Console\Formatter\Color\ColorInterface;

class LightMagenta implements ColorInterface
{
    /**
     * @const string
     */
    public const NAME = 'LightMagenta';

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }
}
