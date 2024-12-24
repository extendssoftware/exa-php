<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Color\LightYellow;

use ExtendsSoftware\ExaPHP\Console\Formatter\Color\ColorInterface;

class LightYellow implements ColorInterface
{
    /**
     * @const string
     */
    public const string NAME = 'LightYellow';

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }
}
