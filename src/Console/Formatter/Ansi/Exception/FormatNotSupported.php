<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Ansi\Exception;

use ExtendsSoftware\ExaPHP\Console\Formatter\Format\FormatInterface;
use RuntimeException;

class FormatNotSupported extends RuntimeException
{
    /**
     * @param FormatInterface $format
     */
    public function __construct(FormatInterface $format)
    {
        parent::__construct(sprintf('Format "%s" is not supported.', $format->getName()));
    }
}
