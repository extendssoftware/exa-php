<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Color\Blue;

use ExtendsSoftware\ExaPHP\Console\Formatter\Color\ColorInterface;

class Blue implements ColorInterface
{
    /**
     * @const string
     */
    public const string NAME = 'Blue';

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }
}
