<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger\Filter;

use ExtendsSoftware\ExaPHP\Logger\LogInterface;

interface FilterInterface
{
    /**
     * Check if log must be filtered.
     *
     * True is returned when log must be filtered, false instead.
     *
     * @param LogInterface $log
     *
     * @return bool
     */
    public function filter(LogInterface $log): bool;
}
