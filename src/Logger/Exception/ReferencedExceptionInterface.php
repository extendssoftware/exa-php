<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Exception;

use Throwable;

interface ReferencedExceptionInterface extends Throwable
{
    /**
     * Get reference for logged exception.
     *
     * @return string
     */
    public function getReference(): string;
}
