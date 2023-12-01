<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\ProblemDetails;

class ProblemDetails implements ProblemDetailsInterface
{
    /**
     * Problem constructor.
     *
     * @param string       $type
     * @param string       $title
     * @param string       $detail
     * @param int          $status
     * @param string|null  $instance
     * @param mixed[]|null $additional
     */
    public function __construct(
        private readonly string $type,
        private readonly string $title,
        private readonly string $detail,
        private readonly int $status,
        private readonly ?string $instance = null,
        private readonly ?array $additional = null
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @inheritDoc
     */
    public function getDetail(): string
    {
        return $this->detail;
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @inheritDoc
     */
    public function getInstance(): ?string
    {
        return $this->instance;
    }

    /**
     * @inheritDoc
     */
    public function getAdditional(): ?array
    {
        return $this->additional;
    }
}
