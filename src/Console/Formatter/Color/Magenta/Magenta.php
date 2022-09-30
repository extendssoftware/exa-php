<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Color\Magenta;

use ExtendsSoftware\ExaPHP\Console\Formatter\Color\ColorInterface;

class Magenta implements ColorInterface
{
    /**
     * @const string
     */
    public const NAME = 'Magenta';

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }
}
