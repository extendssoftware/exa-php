<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Color\LightBlue;

use ExtendsSoftware\ExaPHP\Console\Formatter\Color\ColorInterface;

class LightBlue implements ColorInterface
{
    /**
     * @const string
     */
    public const NAME = 'LightBlue';

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }
}
