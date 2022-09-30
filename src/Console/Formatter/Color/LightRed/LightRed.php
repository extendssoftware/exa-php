<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Color\LightRed;

use ExtendsSoftware\ExaPHP\Console\Formatter\Color\ColorInterface;

class LightRed implements ColorInterface
{
    /**
     * @const string
     */
    public const NAME = 'LightRed';

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }
}
