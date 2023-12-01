<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger;

use DateTime;
use ExtendsSoftware\ExaPHP\Logger\Priority\Critical\CriticalPriority;
use ExtendsSoftware\ExaPHP\Logger\Priority\PriorityInterface;

class Log implements LogInterface
{
    /**
     * Create new log.
     *
     * @param string                 $message
     * @param PriorityInterface|null $priority
     * @param DateTime|null          $datetime
     * @param mixed[]                $metaData
     */
    public function __construct(
        private string $message,
        private readonly ?PriorityInterface $priority = null,
        private readonly ?DateTime $datetime = null,
        private ?array $metaData = []
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
    public function getDateTime(): DateTime
    {
        return $this->datetime ?? new DateTime();
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
}
