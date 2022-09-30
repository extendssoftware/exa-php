<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Format\Reverse;

use ExtendsSoftware\ExaPHP\Console\Formatter\Format\FormatInterface;

class Reverse implements FormatInterface
{
    /**
     * @const string
     */
    public const NAME = 'Reverse';

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }
}
