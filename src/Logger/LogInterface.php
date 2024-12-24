<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger;

use DateTime;
use ExtendsSoftware\ExaPHP\Logger\Priority\PriorityInterface;
use Throwable;

interface LogInterface
{
    /**
     * Actual log message.
     *
     * @return string
     */
    public function getMessage(): string;

    /**
     * Log priority.
     *
     * @return PriorityInterface
     */
    public function getPriority(): PriorityInterface;

    /**
     * Datetime when log happened.
     *
     * @return DateTime
     */
    public function getDateTime(): DateTime;

    /**
     * Get extra metadata.
     *
     * @return mixed[]
     */
    public function getMetaData(): array;

    /**
     * Get throwable.
     *
     * @return Throwable|null
     */
    public function getThrowable(): ?Throwable;

    /**
     * Return new log with message.
     *
     * @param string $message
     *
     * @return LogInterface
     */
    public function withMessage(string $message): LogInterface;

    /**
     * Return new log with metaData.
     *
     * @param mixed[] $metaData
     *
     * @return LogInterface
     */
    public function withMetaData(array $metaData): LogInterface;

    /**
     * Return new log with key and value added to the metadata.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return LogInterface
     */
    public function andMetaData(string $key, mixed $value): LogInterface;

    /**
     * Return new log with throwable.
     *
     * @param Throwable|null $throwable
     *
     * @return LogInterface
     */
    public function withThrowable(Throwable $throwable = null): LogInterface;
}
