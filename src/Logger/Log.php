<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger;

use DateTimeImmutable;
use ExtendsSoftware\ExaPHP\Logger\Priority\Critical\CriticalPriority;
use ExtendsSoftware\ExaPHP\Logger\Priority\PriorityInterface;
use Throwable;

class Log implements LogInterface
{
    /**
     * Create new log.
     *
     * @param string                 $message
     * @param PriorityInterface|null $priority
     * @param DateTimeImmutable|null $datetime
     * @param mixed[]                $metaData
     * @param Throwable|null         $throwable
     */
    public function __construct(
        private string $message,
        private readonly ?PriorityInterface $priority = null,
        private readonly ?DateTimeImmutable $datetime = null,
        private ?array $metaData = [],
        private ?Throwable $throwable = null,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @inheritDoc
     */
    public function getPriority(): PriorityInterface
    {
        return $this->priority ?? new CriticalPriority();
    }

    /**
     * @inheritDoc
     */
    public function getDateTime(): DateTimeImmutable
    {
        return $this->datetime ?? new DateTimeImmutable();
    }

    /**
     * @inheritDoc
     */
    public function getMetaData(): array
    {
        return $this->metaData ?? [];
    }

    /**
     * @inheritDoc
     */
    public function getThrowable(): ?Throwable
    {
        return $this->throwable;
    }

    /**
     * @inheritDoc
     */
    public function withMessage(string $message): LogInterface
    {
        $log = clone $this;
        $log->message = $message;

        return $log;
    }

    /**
     * @inheritDoc
     */
    public function withMetaData(array $metaData): LogInterface
    {
        $log = clone $this;
        $log->metaData = $metaData;

        return $log;
    }

    /**
     * @inheritDoc
     */
    public function andMetaData(string $key, mixed $value): LogInterface
    {
        $log = clone $this;
        $log->metaData[$key] = $value;

        return $log;
    }

    /**
     * @inheritDoc
     */
    public function withThrowable(Throwable $throwable = null): LogInterface
    {
        $log = clone $this;
        $log->throwable = $throwable;

        return $log;
    }
}
