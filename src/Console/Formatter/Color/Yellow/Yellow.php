<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Color\Yellow;

use ExtendsSoftware\ExaPHP\Console\Formatter\Color\ColorInterface;

class Yellow implements ColorInterface
{
    /**
     * @const string
     */
    public const NAME = 'Yellow';

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }
}
