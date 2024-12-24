<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Ansi\Exception;

use ExtendsSoftware\ExaPHP\Console\Formatter\Color\ColorInterface;
use RuntimeException;

use function sprintf;

class ColorNotSupported extends RuntimeException
{
    /**
     * @param ColorInterface $color
     */
    public function __construct(ColorInterface $color)
    {
        parent::__construct(
            sprintf(
                'Color "%s" is not supported.',
                $color->getName()
            )
        );
    }
}
