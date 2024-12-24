<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Color\Red;

use ExtendsSoftware\ExaPHP\Console\Formatter\Color\ColorInterface;

class Red implements ColorInterface
{
    /**
     * @const string
     */
    public const string NAME = 'Red';

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }
}
