<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Color\LightGray;

use ExtendsSoftware\ExaPHP\Console\Formatter\Color\ColorInterface;

class LightGray implements ColorInterface
{
    /**
     * @const string
     */
    public const NAME = 'LightGray';

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }
}
