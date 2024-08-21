<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Logger;

use ExtendsSoftware\ExaPHP\Logger\Priority\PriorityInterface;
use Throwable;

interface LoggerInterface
{
    /**
     * Log message with priority and metaData.
     *
     * When priority is null a CRIT (2) priority will be used.
     *
     * @param string                 $message
     * @param PriorityInterface|null $priority
     * @param mixed[]|null           $metaData
     * @param Throwable|null         $throwable
     *
     * @return LoggerInterface
     */
    public function log(
        string $message,
        PriorityInterface $priority = null,
        array $metaData = null,
        Throwable $throwable = null,
    ): LoggerInterface;

    /**
     * Log EMERG (0) message.
     *
     * @param string         $message
     * @param mixed[]|null   $metaData
     * @param Throwable|null $throwable
     *
     * @return LoggerInterface
     */
    public function emerg(string $message, array $metaData = null, Throwable $throwable = null): LoggerInterface;

    /**
     * Log ALERT (1) message.
     *
     * @param string         $message
     * @param mixed[]|null   $metaData
     * @param Throwable|null $throwable
     *
     * @return LoggerInterface
     */
    public function alert(string $message, array $metaData = null, Throwable $throwable = null): LoggerInterface;

    /**
     * Log CRIT (2) message.
     *
     * @param string         $message
     * @param mixed[]|null   $metaData
     * @param Throwable|null $throwable
     *
     * @return LoggerInterface
     */
    public function crit(string $message, array $metaData = null, Throwable $throwable = null): LoggerInterface;

    /**
     * Log ERROR (3) message.
     *
     * @param string         $message
     * @param mixed[]|null   $metaData
     * @param Throwable|null $throwable
     *
     * @return LoggerInterface
     */
    public function error(string $message, array $metaData = null, Throwable $throwable = null): LoggerInterface;

    /**
     * Log WARNING (4) message.
     *
     * @param string         $message
     * @param mixed[]|null   $metaData
     * @param Throwable|null $throwable
     *
     * @return LoggerInterface
     */
    public function warning(string $message, array $metaData = null, Throwable $throwable = null): LoggerInterface;

    /**
     * Log NOTICE (5) message.
     *
     * @param string         $message
     * @param mixed[]|null   $metaData
     * @param Throwable|null $throwable
     *
     * @return LoggerInterface
     */
    public function notice(string $message, array $metaData = null, Throwable $throwable = null): LoggerInterface;

    /**
     * Log INFO (6) message.
     *
     * @param string         $message
     * @param mixed[]|null   $metaData
     * @param Throwable|null $throwable
     *
     * @return LoggerInterface
     */
    public function info(string $message, array $metaData = null, Throwable $throwable = null): LoggerInterface;

    /**
     * Log DEBUG (7) message.
     *
     * @param string         $message
     * @param mixed[]|null   $metaData
     * @param Throwable|null $throwable
     *
     * @return LoggerInterface
     */
    public function debug(string $message, array $metaData = null, Throwable $throwable = null): LoggerInterface;
}
