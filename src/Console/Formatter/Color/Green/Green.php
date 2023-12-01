<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Color\Green;

use ExtendsSoftware\ExaPHP\Console\Formatter\Color\ColorInterface;

class Green implements ColorInterface
{
    /**
     * @const string
     */
    public const NAME = 'Green';

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }
}
