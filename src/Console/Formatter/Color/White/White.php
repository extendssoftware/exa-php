<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Color\White;

use ExtendsSoftware\ExaPHP\Console\Formatter\Color\ColorInterface;

class White implements ColorInterface
{
    /**
     * @const string
     */
    public const string NAME = 'White';

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }
}
