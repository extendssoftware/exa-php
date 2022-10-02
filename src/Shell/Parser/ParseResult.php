<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell\Parser;

class ParseResult implements ParseResultInterface
{
    /**
     * Create new parse result.
     *
     * @param mixed[] $parsed
     * @param mixed[] $remaining
     * @param bool    $strict
     */
    public function __construct(
        private readonly array $parsed,
        private readonly array $remaining,
        private readonly bool  $strict
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getParsed(): array
    {
        return $this->parsed;
    }

    /**
     * @inheritDoc
     */
    public function getRemaining(): array
    {
        return $this->remaining;
    }

    /**
     * @inheritDoc
     */
    public function isStrict(): bool
    {
        return $this->strict;
    }
}
