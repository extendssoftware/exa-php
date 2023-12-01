<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\RateLimiting\Quota;

interface QuotaInterface
{
    /**
     * If request is allowed.
     *
     * @return bool
     */
    public function isConsumed(): bool;

    /**
     * Get limit.
     *
     * @return int
     */
    public function getLimit(): int;

    /**
     * Get remaining.
     *
     * @return int
     */
    public function getRemaining(): int;

    /**
     * Get reset.
     *
     * @return int
     */
    public function getReset(): int;
}
