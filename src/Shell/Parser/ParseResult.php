<?php
declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Shell\Parser;

readonly class ParseResult implements ParseResultInterface
{
    /**
     * Create new parse result.
     *
     * @param mixed[] $parsed
     * @param mixed[] $remaining
     * @param bool    $strict
     */
    public function __construct(private array $parsed, private array $remaining, private bool $strict)
    {
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
