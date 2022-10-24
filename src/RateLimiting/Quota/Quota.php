<?php

namespace ExtendsSoftware\ExaPHP\RateLimiting\Quota;

class Quota implements QuotaInterface
{
    /**
     * Quota constructor.
     *
     * @param bool $isAllowed
     * @param int  $limit
     * @param int  $remaining
     * @param int  $reset
     */
    public function __construct(
        private readonly bool $isAllowed,
        private readonly int  $limit,
        private readonly int  $remaining,
        private readonly int  $reset
    ) {
    }

    /**
     * @inheritDoc
     */
    public function isConsumed(): bool
    {
        return $this->isAllowed;
    }

    /**
     * @inheritDoc
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @inheritDoc
     */
    public function getRemaining(): int
    {
        return $this->remaining;
    }

    /**
     * @inheritDoc
     */
    public function getReset(): int
    {
        return $this->reset;
    }
}
