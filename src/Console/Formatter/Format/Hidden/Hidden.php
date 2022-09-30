<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Format\Hidden;

use ExtendsSoftware\ExaPHP\Console\Formatter\Format\FormatInterface;

class Hidden implements FormatInterface
{
    /**
     * @const string
     */
    public const NAME = 'Hidden';

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return self::NAME;
    }
}
